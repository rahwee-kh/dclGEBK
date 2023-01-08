<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableFive extends Model
{
    use HasFactory;
    protected $table = 'table_fives';
    protected $fillable = ['x', 'tableID', 'x1', 'domain'];
}
