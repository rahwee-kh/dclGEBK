<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    use HasFactory;
    protected $table = 'user_datas';
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'nonce',
        'eth_address',
        'role',
        'balance',
        'payment',
        'bonus',
        'vote'
    ];

    /**
     * Get the bets for the user.
     */
    public function bets()
    {
        return $this->hasMany(Bet::class);
    }
}
