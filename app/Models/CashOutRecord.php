<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashOutRecord extends Model
{
    use HasFactory;
    protected $table = 'cash_out_records';
    protected $fillable = [
        'record_id',
        'eth_address',
        'balance',
        'status',
        'approve_at'
    ];
}
