<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanySettingSeeder extends Seeder
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
            $data = [
                [
                    'id'            => 1,
                    'setting_name'  => 'Company Name',
                    'value'         => 'Buntung Hoki 88',
                    'slug'          => 'company_name',
                ],
                [
                    'id'            => 2,
                    'setting_name'  => 'Company Logo',
                    'value'         => null,
                    'slug'          => 'company_logo',
                ],
            ];

            foreach ($data as $val) {
                CompanySetting::updateOrCreate([
                    'id'    => $val['id'],
                ], $val);
            }
            DB::commit();
        } catch (\Exception $err) {
            DB::rollBack();
            Log::info($err->getMessage());
        }
    }
}
