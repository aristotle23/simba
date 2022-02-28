<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\User;
use App\Models\Transaction;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;

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
                "currency" => "GBP"
            ],
            [
                "symbol" => "₦",
                "currency" => "NGN"
            ]
        ]);
        $users = [
            ["name" => "User One", "email" => "user1@gmail.com", "password" => "password"],
            ["name" => "User Two", "email" => "user2@gmail.com", "password" => "password"],
            ["name" => "User Three", "email" => "user3@gmail.com", "password" => "password"],
        ];
        foreach ($users as $data) {
            try {
                DB::beginTransaction();
                $user =  User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);
                $currencies = Currency::get();
                foreach ($currencies as $currency) {
                    $userAcc = $user->accounts()->create([
                        "currency_id" => $currency->id,
                        "balance" =>  0.00
                    ]);
                    if ($currency->currency == "USD") {
                        Transaction::create([
                            "source_account_id" => 0,
                            "target_account_id" => $userAcc->id,
                            "source_amount" => 1000,
                            "confirmed" => true
                        ]);
                        $userAcc->balance =  1000;
                        $userAcc->save();
                    }
                }

                DB::commit();
                return $user;
            } catch (Exception $e) {
                DB::rollBack();
                throw new Exception($e, 1);
            }
        }
    }
}
