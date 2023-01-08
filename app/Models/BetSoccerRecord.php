<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//depecated
class BetSoccerRecord extends Model
{
    use HasFactory;
    protected $table = 'bet_soccer_records';
    protected $fillable = [
        'eth_address',
        'match_id',
        'selection',
        'bet_type',
        'chips'];


}
