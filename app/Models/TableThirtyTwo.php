<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableThirtyTwo extends Model
{
    use HasFactory;
    protected $table = 'table_thirty_twos';
    protected $fillable = ['x', 'tableID', 'x1', 'domain'];
}
