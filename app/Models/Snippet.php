<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Snippet extends Model
{
    protected $fillable = [
        'user_id',
        'language',
        'code',
        'description',
        'channel_id'
    ];
}