<?php

namespace App\Http\Controllers;

use App\Models\UserBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Alert;
use App\Models\BankPayment;
use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class AdminBankController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin-bank-create',['only' => 'store']);
        $this->middleware('can:admin-bank-show',['only' => 'detail']);
        $this->middleware('can:admin-bank-edit',['only' => 'update']);
        $this->middleware('can:admin-bank-delete',['only' => 'delete']);
    }

    public function data(Request $request)
    {
        if (isset($request->admin_id) && $request->admin_id != '') {
            $bankAdmin = UserBank::where('user_id', $request->admin_id)->with('user', 'paymentType', 'bankPayment');
        } else {
            $bankAdmin = UserBank::with('user', 'paymentType', 'bankPayment');
            if ($request->has('admin_id') && !is_null($request->admin_id)) {
                $bankAdmin->where('user_id', $request->admin_id);
            }
        }

        if ($request->has('payment') && $request->payment != '') {
            $bankAdmin->where('payment_type_id', $request->payment);
        }

        if ($request->has('bank') && $request->bank != '') {
            $bankAdmin->where('bank_payment_id', $request->bank);
        }

        if ($request->ajax()) {
            return DataTables::of($bankAdmin->latest())
            ->addIndexColumn()
            ->addColumn('status_badge', function ($row) {
                if ($row->status == 1) {
                    return "<span class='badge badge-success'>Active</span>";
                } else {
                    return "<span class='badge badge-danger'>Not Active</span>";
                }
            })
            ->addColumn('payment_types', function ($row) {
                return $row->paymentType->name;
            })
            ->addColumn('bank_name', function ($row) {
                return $row->bankPayment->name;
            })
            ->addColumn('action', function ($row) {
                if (auth()->user()->can('admin-bank-show')) {
                    $showButton = "<a onclick='showUserBank($row->id)' class='cursor-allowed mr-2'><i class='fas fa-eye'></i></a>";
                } else {
                    $showButton = "<a href='#' class='cursor-not-allowed mr-2'><i class='fas fa-eye'></i></a>";
                }

                if (auth()->user()->can('admin-bank-delete')) {
                    $deleteButton = "<a onclick='deleteUserBank($row->id)' class='cursor-allowed mr-2'><i class='fas fa-trash'></i></a>";
                } else {
                    $deleteButton = "<a href='#' class='cursor-not-allowed mr-2'><i class='fas fa-trash'></i></a>";
                }

                return $showButton.$deleteButton;
            })
            ->addColumn('admin', function ($row) {
                if (auth()->user()->can('admin-management-show')) {
                    return "<div class='td-text'>
                        <a href='".route('admin.detail', $row->user_id)."'>".$row->user->fullname." | ".$row->user->email."</a>
                    </div>";
                }
                return "<div class='td-text'>".$row->user->fullname." | ".$row->user->email."</div>";
            })
            ->rawColumns(['status_badge', 'payment_types', 'bank_name', 'action', 'admin'])
            ->make(true);
        }
    }

    public function getBankPayments(Request $request)
    {
        $bankPayment = BankPayment::where('payment_type_id', $request->payment_id)->get();
        return response()->json($bankPayment);    
    }

    public function create(Request $request)
    {
        $paymentTypes = PaymentType::select('id', 'name')->get();
        if ($request->has('admin_id') && $request->admin_id != null) {
            $adminId = $request->admin_id;
            $admin = User::find($adminId);
            return view('pages.admin-bank-create', compact('admin', 'paymentTypes'));
        }

        $admins = User::select('id', 'fullname')->get();
        return view('pages.admin-bank-create', compact('admins', 'paymentTypes'));
    }

    public function detail($id)
    {
        $userBank = UserBank::with('user', 'paymentType', 'bankPayment')->find($id);
        $data = [
            'id' => $id,
            'account_name'   => $userBank->account_name,
            'account_number' => $userBank->account_number,
            'bank_payment_id' => $userBank->bank_payment_id,
            'payment_type_id' => $userBank->payment_type_id,
            'status'         => $userBank->status,
            'user'          => $userBank->user,
            'payment_tye' => $userBank->paymentType,
            'bank_payment' => $userBank->bankPayment,
            'user_id' => $userBank->user_id,
            'can_edit' => auth()->user()->can('admin-bank-edit'),
        ];
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'         => 'required',
            'payment_type_id' => 'required',
            'bank_payment_id' => 'required',
            'account_name'    => 'required',
            'account_number'  => 'required',
        ]);

        DB::beginTransaction();
        try {
            UserBank::create($request->all());
            DB::commit();
            Alert::success('Success', 'Data created successfully');
            return redirect()->back();
        } catch (\Exception $err) {
            DB::rollBack();
            Alert::error('Internal Server Error', $err->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'payment_type_id' => 'required',
            'bank_payment_id' => 'required',
            'account_name'    => 'required',
            'account_number'  => 'required',
            'status'          => '',
        ]);

        DB::beginTransaction();
        try {
            UserBank::find($id)->update($request->all());
            DB::commit();
            return response()->json([
                'status'  => true,
                'message' => 'Data updated successfully',
            ]);
        } catch (\Exception $err) {
            DB::rollBack();
            Log::info($err->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Internal Server Error',
            ]);
        }
    }

    public function delete($id)
    {
        UserBank::find($id)->delete();
        return response()->json(['status' => true]);
    }
}
