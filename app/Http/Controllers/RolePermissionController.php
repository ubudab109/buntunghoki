<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\DataTables;
use Alert;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:role-management-list',['only' => 'data']);
        $this->middleware('can:role-management-show',['only' => 'detail']);
        $this->middleware('can:role-management-create',['only' => 'create|store']);
    }

    public function data()
    {
        $data = DB::table('roles')->get();
        return DataTables::of($data)
        ->addIndexColumn()
        ->editColumn('role', function ($row) {
            return ucwords($row->name);
        })
        ->addColumn('action', function ($row) {
            if (auth()->user()->can('role-management-list')) {
                $cursor = 'cursor-allowed';
                $route = route('role.detail', $row->id);
            } else {
                $cursor = 'cursor-not-allowed';
                $route = '#';
            }
            return "<a href='$route' class='$cursor'><i class='fas fa-eye'></i></a>";
        })->rawColumns(['action', 'role'])
        ->make(true);
    }

    public function detail($id)
    {
        $role = Role::find($id);
        $dataPermissions = [];
        $permissionScopes = DB::table('permissions')->where('parent_id', null)->get();
        foreach ($permissionScopes as $scope) {
            $permissions = DB::table('permissions')->where('parent_id', $scope->id)->get();
            foreach ($permissions as $permission) {
                $dataPermissions[$scope->display][] = [
                    'id'        => $permission->id,
                    'name'      => $permission->name,
                    'display'   => $permission->display,
                    'parent_id' => $permission->parent_id,
                    'is_assign' => $role->hasPermissionTo($permission->name),                  
                ];
            }
        }
        return view('pages.role-detail', compact('role', 'dataPermissions'));
    }

    public function create()
    {
        $dataPermissions = [];
        $permissionScopes = DB::table('permissions')->where('parent_id', null)->get();
        foreach ($permissionScopes as $scope) {
            $permissions = DB::table('permissions')->where('parent_id', $scope->id)->get();
            foreach ($permissions as $permission) {
                $dataPermissions[$scope->display][] = [
                    'id'        => $permission->id,
                    'name'      => $permission->name,
                    'display'   => $permission->display,
                    'parent_id' => $permission->parent_id,                  
                ];
            }
        }
        return view('pages.role-create', compact('dataPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'permissions' => 'array|required',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->name]);
            $permData = [];
            foreach($request->input('permissions') as $permission) {
                array_push($permData, $permission);
            }
            $role->syncPermissions($permData);
            DB::commit();
            Alert::success('Success', 'Role Created Successfully');
            return redirect()->route('role.index');
        } catch (\Exception $err) {
            DB::rollBack();
            Alert::error('Internal Server Error', $err->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::find($id);
            $permData = [];
            foreach($request->input('permissions') as $permission) {
               array_push($permData, $permission);
            }
            $role->syncPermissions($permData);
            $role->update(['name' => $request->name]);
            Alert::success('Success', 'Role Updated Successfully');
            DB::commit();
            return redirect()->back();
        } catch (\Exception $err) {
            Alert::error('Internal Server Error', $err->getMessage());
            DB::rollBack();
            return redirect()->back();
        }
    }
}
