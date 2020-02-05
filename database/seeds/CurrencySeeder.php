<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            [
                'code' => 'IDR',
                'name' => 'Indonesian Rupiah'
            ],
            [
                'code' => 'SGD',
                'name' => 'Singapore Dollar'
            ],
            [
                'code' => 'USD',
                'name' => 'US Dollar'
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro'
            ],
        ];

        foreach($currencies as $currency) {
            DB::table('currencies')->insert([
                'code' => $currency['code'],
                'name' => $currency['name'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}