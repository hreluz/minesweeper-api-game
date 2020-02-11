<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GridLog extends Model
{
    protected $fillable = [
        'grid', 'x', 'y','cells_opened'
    ];
}
