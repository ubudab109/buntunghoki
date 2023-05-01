<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:transaction-list',['only' => 'data']);
        $this->middleware('can:transaction-show',['only' => 'detail']);
        $this->middleware('can:transaction-update',['only' => 'update']);
        $this->middleware('can:transaction-delete',['only' => 'delete']);
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $transaction = Transaction::
            with(['member', 'admin', 'adminBank', 'memberBank']);

            if ($request->has('type') && $request->type != '') {
                $transaction->where('type', $request->type);
                if ($request->type == 'deposit') {
                    $transaction->where('admin_id', Auth::user()->id);
                }
            }
            return DataTables::of($transaction->orderBy('id', 'desc')
            ->get())
            ->addIndexColumn()
            ->addColumn('members', function ($row) {
                return $row->member->username;
            })
            ->addColumn('adminBanks', function ($row) {
                if ($row->adminBank) {
                    return $row->adminBank->bankPayment->name.' - '. $row->adminBank->account_name.' - '. $row->adminBank->account_number;
                }
                return null;
            })
            ->addColumn('memberBanks', function ($row) {
                return $row->memberBank->bankPayment->name.' - '. $row->memberBank->account_name.' - '. $row->memberBank->account_number;
            })
            ->editColumn('amount', function ($row) {
                return rupiah($row->amount);
            })
            ->addColumn('action', function ($row) {
                if ($row->status == 0 || $row->status == '0') {

                    if (auth()->user()->can('transaction-update')) {
                        $showButton = "<a onclick='approve($row->id)' class='cursor-allowed mr-2'><i class='fas fa-check'></i></a>";
                        $deleteButton = "<a onclick='reject($row->id)' class='cursor-allowed mr-2' style='font-size: 26px'>x</a>";
                    } else {
                        $showButton = "<a href='#' class='cursor-not-allowed mr-2'><i class='fas fa-check'></i></a>";
                        $deleteButton = "<a href='#' class='cursor-not-allowed mr-2'><i class='fa-solid fa-xmark-to-slot'></i></a>";
                    }
                    return $showButton.$deleteButton;
                }

                return null;

            })
            ->rawColumns(['members', 'adminBanks', 'memberBanks', 'action', 'amount'])
            ->make(true);
        }
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);
        DB::beginTransaction();
        try {
            if ($transaction->status == '0' || $transaction->status == 0) {
                $member = DB::table('members')->where('id', $transaction->member_id)->first();
                if ($request->status == '1') {
                    if ($transaction->type == 'deposit') {
                        DB::table('members')->where('id', $transaction->member_id)
                        ->increment('balance', $transaction->amount);
                    } else if ($transaction->type == 'withdraw') {
                        if ($transaction->amount > $member->balance) {
                            return response()->json([
                                'status'    => false,
                                'message'   => 'Insufficient Balance',
                            ]);
                        }
                        // DB::table('members')->where('id', $transaction->member_id)
                        // ->decrement('balance', $transaction->amount);
                    }
                } else if ($request->status == '2') {
                    DB::table('members')->where('id', $transaction->member_id)
                        ->increment('balance', $transaction->amount);
                }
                $transaction->update(['status' => $request->status]);
            }
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
}
