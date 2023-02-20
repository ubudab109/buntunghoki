<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'fullname'      => 'Superadmin',
                'username'      => 'superadmin',
                'phone_number'  => '0858870283452',
                'email'         => 'super@admin.com',
                'password'      => Hash::make('123123123'),
            ]);

            $role = Role::create([
                'name' => 'superadmin',
            ]);

            foreach(Permission::where('parent_id', '!=' , null)->get() as $permission) {
                $role->givePermissionTo($permission);
            }
            $user->assignRole($role);
            
            DB::commit();
        } catch (\Exception $err) {
            DB::rollBack();
            Log::info($err->getMessage());
        }
    }
}
