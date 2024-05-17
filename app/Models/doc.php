<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doc extends Model
{
    protected $table = 'docs';

    protected $fillable = [
        'name',
        'size',
        'url',
        'type',
        'content',
    ];

}
