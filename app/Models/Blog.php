<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    // 可填字段白名单
    protected $fillable = [
        'title', 'content'
    ];

    // 绑定1:n关系
    public function comments() {
        return $this->hasMany('App\Models\Comment'); // 1 hasMany n
    }
}
