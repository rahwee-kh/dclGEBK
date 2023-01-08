<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//depecated
class FifaMatch extends Model
{
    use HasFactory;
    protected $table = 'fifa_matchs';
    protected $fillable = [
        'match_id',
        'sport_key',
        'sport_title',
        'commence_time',
        'completed',
        'home_team',
        'away_team',
        'home_score',
        'away_score',
        'result',
        'last_update'
    ];


}
