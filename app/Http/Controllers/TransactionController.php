<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = Transaction::whereIn("source_account_id",function($query){
            $query->select("id")
            ->from("accounts")
            ->where("user_id",auth()->user()->id);
        })->orWhereIn("target_account_id",function($query){
            $query->select("id")
            ->from("accounts")
            ->where("user_id",auth()->user()->id);
        })->get();
        $transactions = [];
        foreach($data as $index => $item){
            $tran = [$index + 1];
            $source_account = Account::with(["user","currency"])->where("id",$item->source_account_id)->first();
            $target_account = Account::with(["user","currency"])->where("id",$item->target_account_id)->first();


            $to = $target_account->user->id == auth()->user()->id ? "Me" : $target_account->user->name;
            

            if(is_null($source_account)){
                array_push($tran, null);
                array_push($tran,  $to );
                $amount = $item->source_amount * $item->exchange_rate;
                array_push($tran, "+".$amount);
                array_push($tran, $target_account->currency->currency);
            }else{
                if($source_account->user->id == auth()->user()->id){
                    array_push($tran,  "Me" );
                    array_push($tran,  $to );
                    array_push($tran, "-".$item->source_amount);
                    array_push($tran, $source_account->currency->currency);
                }else{
                    
                    $amount = $item->source_amount * $item->exchange_rate;
                    array_push($tran, $source_account->user->name, $to, "+".$amount, $target_account->currency->currency,);
                    
                }
            }
            array_push($tran, $item->created_at->format("F d, Y H:i"),$item->updated_at->format("F d, Y H:i"),$item->confirmed);
            array_push($transactions, $tran);
        }
        $accounts = Account::with("currency")->where("user_id", auth()->user()->id)->get();
        
        return view('home',['data' => $transactions,'accounts' => $accounts ]);
    }
}
