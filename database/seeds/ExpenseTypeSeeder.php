<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ExpenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expenseTypes = [
            [
                'name' => 'Transportation',
                'description' => 'Expenses related to transportation.'
            ],
            [
                'name' => 'Meals',
                'description' => 'Expenses related to meals.'
            ]
        ];

        foreach($expenseTypes as $expenseType) {
            DB::table('expense_types')->insert([
                'name' => $expenseType['name'],
                'description' => $expenseType['description'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
