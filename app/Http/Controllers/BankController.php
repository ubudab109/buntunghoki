<?php

namespace App\Http\Controllers;

use App\Models\BankPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class BankController extends Controller
{
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $banks = BankPayment::with('paymentType:id,name');
            if (isset($request->payment) && $request->payment != 'all') {
                $banks->where('payment_type_id', $request->payment);
            }
            return DataTables::of($banks->get())
            ->addIndexColumn()
            ->addColumn('payment_name', function ($row) {
                return $row->paymentType->name;
            })
            ->addColumn('status_badge', function ($row) {
                if ($row->status == 1) {
                    return "<span class='badge badge-success'>Active</span>";
                } else {
                    return "<span class='badge badge-danger'>Not Active</span>";
                }
            })
            ->addColumn('action', function ($row) {
                if (auth()->user()->can('bank-payment-show')) {
                    $showButton = "<a onclick='showBank($row->id)' class='cursor-allowed mr-2'><i class='fas fa-eye'></i></a>";
                } else {
                    $showButton = "<a href='#' class='cursor-not-allowed mr-2'><i class='fas fa-eye'></i></a>";
                }

                if (auth()->user()->can('bank-payment-delete')) {
                    $deleteButton = "<a onclick='deleteBank($row->id)' class='cursor-allowed mr-2'><i class='fas fa-trash'></i></a>";
                } else {
                    $deleteButton = "<a href='#' class='cursor-not-allowed mr-2'><i class='fas fa-trash'></i></a>";
                }

                return $showButton.$deleteButton;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
        }
    }

    public function detail($id)
    {
        $bank = BankPayment::with('paymentType:id,name')->find($id);
        $data = [ 
            'id'            => $bank->id,
            'name'          => $bank->name,
            'payment_id'    => $bank->payment_type_id,
            'payment_name'  => $bank->paymentType->name,
            'status'        => $bank->status,
            'can_edit'      => auth()->user()->can('bank-payment-edit'),
        ];
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required',
            'payment_type_id' => 'required'
        ]);

        DB::beginTransaction();
        try {
            BankPayment::create($request->all());
            DB::commit();
            return response()->json([
                'status'  => true,
                'message' => 'Data created successfully',
            ]);
        } catch (\Exception $err) {
            DB::rollBack();
            Log::info($err->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Internal Server Error'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'            => 'required',
            'payment_type_id' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $bankPayment = BankPayment::find($id);
            $bankPayment->update($request->all());
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
                'message' => 'Internal Server Error'
            ]);
        }
    }

    public function delete($id)
    {
        BankPayment::find($id)->delete();
        return response()->json(['status' => true]);
    }
}
