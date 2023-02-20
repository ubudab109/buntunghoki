<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentTypeSeeder extends Seeder
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
                    'name'  => 'Bank',
                ],
                [
                    'name'  => 'E-Wallet',
                ],
                [
                    'name'  => 'Pulsa',
                ],
                [
                    'name'  => 'Cryptocurrency',
                ]
            ];
            foreach ($data as $val) {
                PaymentType::create($val);
            }
            DB::commit();
        } catch (\Exception $err) {
            Log::info($err->getMessage());
            DB::rollBack();
        }
    }
}
