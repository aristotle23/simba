<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class NewController extends Controller
{
    public function index()
    {
        $currencies = Currency::get();
        $users = User::where("id", "!=", auth()->user()->id)->get();
        return view("new", ['currencies' => $currencies, 'users' => $users]);
    }
    public function new(Request $request)
    {
        $data = $request->validate([
            "source_currency" => "required",
            "target_currency" => "required",
            "recipient" => "required",
            "source_amount" => "required"
        ]);
        
        $xr = 1 ;
        $source_account = auth()->user()->accounts()->with("currency")->where("currency_id", $data['source_currency'])->first();
        $target_account = User::find($data['recipient'])->accounts()->with("currency")->where("currency_id", $data['target_currency'])->first();
        
        if($source_account->currency->currency != $target_account->currency->currency){
            $xrEndpoint = "https://freecurrencyapi.net/api/v2/latest?apikey=21585a10-980c-11ec-a4eb-3b7cfdf814ee&base_currency=".$source_account->currency->currency;
            $xr = Http::get($xrEndpoint)['data'][$target_account->currency->currency];
        }
        

        if ($source_account->balance < $data['source_amount']) {
            return redirect()->back()->with("error", "Insufficient Balance");
        }
        $trans = Transaction::create([
            "source_account_id" => $source_account->id,
            "target_account_id" => $target_account->id,
            "exchange_rate" => $xr,
            "source_amount" => round( $data['source_amount'],2),
        ]);
        try {

            DB::beginTransaction();

            $source_account->balance -= $data['source_amount'];
            $target_account->balance += round( $xr * $data['source_amount'] , 2);
            $trans->confirmed = true;

            $source_account->save();
            $target_account->save();
            $trans->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", "Transaction Unsuccessfull");
        }
        return redirect()->back()->with("msg","Transaction Successful");
    }
}
