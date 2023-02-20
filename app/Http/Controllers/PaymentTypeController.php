<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class PaymentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:payment-type-create',['only' => 'store']);
        $this->middleware('can:payment-type-show',['only' => 'detail']);
        $this->middleware('can:payment-type-edit',['only' => 'update']);
        $this->middleware('can:payment-type-delete',['only' => 'delete']);
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $paymentType = PaymentType::all();
            return DataTables::of($paymentType)
            ->addIndexColumn()
            ->addColumn('status_badge', function ($row) {
                if ($row->status == 1) {
                    return "<span class='badge badge-success'>Active</span>";
                } else {
                    return "<span class='badge badge-danger'>Not Active</span>";
                }
            })
            ->addColumn('action', function ($row) {
                if (auth()->user()->can('payment-type-show')) {
                    $showButton = "<a onclick='showPaymentType($row->id)' class='cursor-allowed mr-2'><i class='fas fa-eye'></i></a>";
                } else {
                    $showButton = "<a href='#' class='cursor-not-allowed mr-2'><i class='fas fa-eye'></i></a>";
                }

                if (auth()->user()->can('payment-type-delete')) {
                    $deleteButton = "<a onclick='deletePayment($row->id)' class='cursor-allowed mr-2'><i class='fas fa-trash'></i></a>";
                } else {
                    $deleteButton = "<a href='#' class='cursor-not-allowed mr-2'><i class='fas fa-trash'></i></a>";
                }

                return $showButton.$deleteButton;

            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            PaymentType::create($request->all());
            DB::commit();
            return response()->json([
                'status'    => true,
                'message'   => 'Data created successfully',
            ]);
        } catch (\Exception $err) {
            DB::rollBack();
            Log::info($err->getMessage());
            return response()->json([
                'status'    => false,
                'message'   => 'Internal Server Error',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $paymentType = PaymentType::find($id);
            $paymentType->update($request->all());
            DB::commit();
            return response()->json([
                'status'    => true,
                'message'   => 'Data updated successfully',
            ]);
        } catch (\Exception $err) {
            DB::rollBack();
            Log::info($err->getMessage());
            return response()->json([
                'status'    => false,
                'message'   => 'Internal Server Error',
            ]);
        }
    }


    public function detail($id)
    {
        $paymentType = PaymentType::find($id);
        $data = [
            'id'        => $id,
            'name'      => $paymentType->name,
            'status'    => $paymentType->status,
            'can_edit'  => auth()->user()->can('payment-type-edit'),
        ];
        return response()->json($data);
    }

    public function delete($id)
    {
        PaymentType::find($id)->delete();
        return response()->json(['status' => true]);
    }
}
