<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//2022_11_19_121214_create_bet_fifa_records_table
class BetFifaRecord extends Model
{
    use HasFactory;
    protected $table = 'bet_fifa_records';
    protected $fillable = [
        'eth_address',
        'match_id',
        'selection',
        'bet_type',
        'chips',
        'commence_time',
        'price',
        'result'];


}
