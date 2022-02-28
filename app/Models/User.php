<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function accounts(){
        return $this->hasMany(Account::class);
    }
    public function transactions(){
        return Transaction::with(['sourceAccount','targetAccount'])->whereIn("source_account_id",function($query){
            $query->select("id")
            ->from("accounts")
            ->where("user_id",$this->id);
        })->orWhereIn("target_account_id",function($query){
            $query->select("id")
            ->from("accounts")
            ->where("user_id",$this->id);
        })->find();
    }
}