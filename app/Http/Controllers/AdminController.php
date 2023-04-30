<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Alert;
use App\Models\PaymentType;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin-management-create',['only' => 'create|store']);
        $this->middleware('can:admin-management-show',['only' => 'detail']);
        $this->middleware('can:admin-management-edit',['only' => 'update']);
        $this->middleware('can:admin-management-delete',['only' => 'delete']);
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $admin = User::with('roles')->where('id', '!=', auth()->user()->id)->get();
            return DataTables::of($admin)
            ->addIndexColumn()
            ->addColumn('status_badge', function ($row) {
                if ($row->status == 1) {
                    return "<div class='bg-teal color-palette'> <span>Active</span> </div>";
                } else {
                    return "<div class='bg-maroon color-palette'> <span>Not Active</span> </div>";
                }
            })
            ->addColumn('action', function ($row) {
                if (auth()->user()->can('admin-management-show')) {
                    $showButton = "<a href='". route('admin.detail', $row->id) ."' class='cursor-allowed mr-2'><i class='fas fa-eye'></i></a>";
                } else {
                    $showButton = "<a href='#' class='cursor-not-allowed mr-2'><i class='fas fa-eye'></i></a>";
                }

                if (auth()->user()->can('admin-management-delete')) {
                    $deleteButton = "<a onclick='deleteAdmin($row->id)' class='cursor-allowed mr-2'><i class='fas fa-trash'></i></a>";
                } else {
                    $deleteButton = "<a href='#' class='cursor-not-allowed mr-2'><i class='fas fa-trash'></i></a>";
                }

                return $showButton.$deleteButton;
            })
            ->addColumn('role', function ($row) {
                if (!empty($row->roles)) {
                    return $row->roles[0]->name;
                }
                return 'Not Have Role';
            })
            ->rawColumns(['status_badge', 'action', 'role'])
            ->make(true);
        }
    }

    public function create()
    {
        $roles = Role::all();
        return view ('pages.admin-create', compact('roles'));
    }

    public function detail($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        $paymentTypes = PaymentType::select('id','name')->get();
        $isCanEdit = auth()->user()->can('admin-management-edit');

        return view('pages.admin-detail', compact('user','roles', 'isCanEdit', 'paymentTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname'      => 'required',
            'email'         => 'required',
            'role'          => 'required',
            'phone_number'  => 'numeric',
            'username'      => 'required',
            'password'      => 'required|min:8',
            'confirm_pass'  => 'required|same:password|min:8',
        ]);

        DB::beginTransaction();
        try {
            $input = $request->except('role');
            $input['password'] = Hash::make($request->password);
            $user = User::create($input);
            $user->assignRole($request->role);
            DB::commit();
            Alert::success('Success', 'Admin created successfully');
            return redirect()->route('admin.index');
        } catch (\Exception $err) {
            DB::rollBack();
            Alert::error('Internal Server Error', $err->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fullname'      => 'required',
            'email'         => 'required',
            'role'          => 'required',
            'phone_number'  => 'numeric',
            'username'      => 'required',
        ]);

        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->removeRole($request->role);
            $user->assignRole($request->role);
            $user->update($request->except('role'));
            DB::commit();
            Alert::success('Success', 'Admin Updated successfully');
            return redirect()->route('admin.detail', $id);
        } catch (\Exception $err) {
            DB::rollBack();
            Alert::error('Internal Server Error', $err->getMessage());
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        User::find($id)->delete();
        return response()->json(['status' => true]);
    }
}
