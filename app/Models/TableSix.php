<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableSix extends Model
{
    use HasFactory;
    protected $table = 'table_sixes';
    protected $fillable = ['x', 'tableID', 'x1', 'domain'];
}
