<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\User;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
            } catch (Exception $e) {
                DB::rollBack();
            }
        }
    }
}
