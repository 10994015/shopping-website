<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Discount::create([
            'code'=>'gogogo',
            'discount_type'=>'percentage',
            'percentage'=> 0.8,
            'discount_value'=> 0,
            'start_date'=>'2023/07/01',
            'end_date'=>'2024/07/01',
            'usage_count'=> 0,
            'minimum_spend'=>300,
        ]);
    }
}
