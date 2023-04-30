<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id'        => 1,
                'parent_id' => null,
                'guard_name'    => 'web',
                'name'      => 'company-setting',
                'display'   => 'Company Setting',
            ],
            [
                'id'        => 2,
                'guard_name'    => 'web',
                'parent_id' => 1,
                'name'      => 'company-setting-list',
                'display'   => 'List',
            ],
            [
                'id'        => 3,
                'guard_name'    => 'web',
                'parent_id' => 1,
                'name'      => 'company-setting-edit',
                'display'   => 'Edit',
            ],
            [
                'id'        => 4,
                'guard_name'    => 'web',
                'parent_id' => null,
                'name'      => 'payment-type',
                'display'   => 'Payment Type',
            ],
            [
                'id'        => 5,
                'guard_name'    => 'web',
                'parent_id' => 4,
                'name'      => 'payment-type-list',
                'display'   => 'List',
            ],
            [
                'id'        => 6,
                'parent_id' => 4,
                'guard_name'    => 'web',
                'name'      => 'payment-type-show',
                'display'   => 'Detail',
            ],
            [
                'id'        => 7,
                'guard_name'    => 'web',
                'parent_id' => 4,
                'name'      => 'payment-type-create',
                'display'   => 'Create',
            ],
            [
                'id'        => 8,
                'guard_name'    => 'web',
                'parent_id' => 4,
                'name'      => 'payment-type-edit',
                'display'   => 'Edit',
            ],
            [
                'id'        => 9,
                'guard_name'    => 'web',
                'parent_id' => 4,
                'name'      => 'payment-type-delete',
                'display'   => 'Delete',
            ],
            [
                'id'        => 10,
                'guard_name'    => 'web',
                'parent_id' => null,
                'name'      => 'bank-payment',
                'display'   => 'Bank Payment',
            ],
            [
                'id'        => 11,
                'guard_name'    => 'web',
                'parent_id' => 10,
                'name'      => 'bank-payment-list',
                'display'   => 'List',
            ],
            [
                'id'        => 12,
                'guard_name'    => 'web',
                'parent_id' => 10,
                'name'      => 'bank-payment-show',
                'display'   => 'Detail',
            ],
            [
                'id'        => 13,
                'parent_id' => 10,
                'guard_name'    => 'web',
                'name'      => 'bank-payment-create',
                'display'   => 'Create',
            ],
            [
                'id'        => 14,
                'parent_id' => 10,
                'guard_name'    => 'web',
                'name'      => 'bank-payment-edit',
                'display'   => 'Edit',
            ],
            [
                'id'        => 15,
                'parent_id' => 10,
                'guard_name'    => 'web',
                'name'      => 'bank-payment-delete',
                'display'   => 'Delete',
            ],
            [
                'id'         => 16,
                'parent_id'  => null,
                'guard_name' => 'web',
                'name'       => 'role-management',
                'display'    => 'Role Management',
            ],
            [
                'id'         => 17,
                'parent_id'  => 16,
                'guard_name' => 'web',
                'name'       => 'role-management-list',
                'display'    => 'List',
            ],
            [
                'id'         => 18,
                'parent_id'  => 16,
                'guard_name' => 'web',
                'name'       => 'role-management-show',
                'display'    => 'Detail',
            ],
            [
                'id'         => 19,
                'parent_id'  => 16,
                'guard_name' => 'web',
                'name'       => 'role-management-create',
                'display'    => 'Create',
            ],
            [
                'id'         => 20,
                'parent_id'  => 16,
                'guard_name' => 'web',
                'name'       => 'role-management-edit',
                'display'    => 'Edit',
            ],
            [
                'id'         => 21,
                'parent_id'  => 16,
                'guard_name' => 'web',
                'name'       => 'role-management-delete',
                'display'    => 'Delete',
            ],
            [
                'id'         => 22,
                'parent_id'  => null,
                'guard_name' => 'web',
                'name'       => 'admin-management',
                'display'    => 'Admin Management',
            ],
            [
                'id'         => 23,
                'parent_id'  => 22,
                'guard_name' => 'web',
                'name'       => 'admin-management-list',
                'display'    => 'List',
            ],
            [
                'id'         => 24,
                'parent_id'  => 22,
                'guard_name' => 'web',
                'name'       => 'admin-management-show',
                'display'    => 'Detail',
            ],
            [
                'id'         => 25,
                'parent_id'  => 22,
                'guard_name' => 'web',
                'name'       => 'admin-management-create',
                'display'    => 'Create',
            ],
            [
                'id'         => 26,
                'parent_id'  => 22,
                'guard_name' => 'web',
                'name'       => 'admin-management-edit',
                'display'    => 'Edit',
            ],
            [
                'id'         => 27,
                'parent_id'  => 22,
                'guard_name' => 'web',
                'name'       => 'admin-management-delete',
                'display'    => 'Delete',
            ],
            [
                'id'         => 28,
                'parent_id'  => null,
                'guard_name' => 'web',
                'name'       => 'admin-bank',
                'display'    => 'Admin Bank',
            ],
            [
                'id'         => 29,
                'parent_id'  => 28,
                'guard_name' => 'web',
                'name'       => 'admin-bank-list',
                'display'    => 'List',
            ],
            [
                'id'         => 30,
                'parent_id'  => 28,
                'guard_name' => 'web',
                'name'       => 'admin-bank-show',
                'display'    => 'Detail',
            ],
            [
                'id'         => 31,
                'parent_id'  => 28,
                'guard_name' => 'web',
                'name'       => 'admin-bank-create',
                'display'    => 'Create',
            ],
            [
                'id'         => 32,
                'parent_id'  => 28,
                'guard_name' => 'web',
                'name'       => 'admin-bank-edit',
                'display'    => 'Edit',
            ],
            [
                'id'         => 33,
                'parent_id'  => 28,
                'guard_name' => 'web',
                'name'       => 'admin-bank-delete',
                'display'    => 'Delete',
            ],
            [
                'id'         => 34,
                'parent_id'  => null,
                'guard_name' => 'web',
                'name'       => 'transaction',
                'display'    => 'Transaction',
            ],
            [
                'id'         => 35,
                'parent_id'  => 34,
                'guard_name' => 'web',
                'name'       => 'transaction-list',
                'display'    => 'Transaction',
            ],
            [
                'id'         => 36,
                'parent_id'  => 34,
                'guard_name' => 'web',
                'name'       => 'transaction-update',
                'display'    => 'Update',
            ],
        ];

        foreach ($data as $perm) {
            $checkExists = DB::table('permissions')->where('id',$perm['id'])->exists();
            if (!$checkExists) {
                Permission::create($perm);
            }
        }
    }
}
