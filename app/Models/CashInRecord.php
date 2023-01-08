<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashInRecord extends Model
{
    use HasFactory;
    protected $table = 'cash_in_records';
    protected $fillable = [
        'transaction_hash',
        'eth_address',
        'balance'
    ];
}
