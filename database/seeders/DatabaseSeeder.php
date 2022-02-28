<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Currency::insert([
            [
                "symbol" => "$",
                "currency" => "USD"
            ],
            [
                "symbol" => "£",
                "currency" => "GDP"
            ],
            [
                "symbol" => "₦",
                "currency" => "NGN"
            ]
        ]);
        
    }
}
