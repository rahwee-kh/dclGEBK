<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'user_name', 
        'bet_type', 
        'bet_price', 
        'est_price', 
        'bet_round', 
        'bet_shoe'];


    /**
     * Get the user that owns the bet.
     */
    public function user()
    {
        return $this->belongsTo(UserData::class);
    }
}
