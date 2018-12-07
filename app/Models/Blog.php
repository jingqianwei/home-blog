<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    // 可填字段白名单
    protected $fillable = [
        'title', 'content'
    ];
}
