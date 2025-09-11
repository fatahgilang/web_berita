<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class author extends Model
{
    protected $fillable = [
        'name',
        'username',
        'avatar',
        'bio',
    ];
    public function news()
    {
        return $this->hasMany(News::class);
    }
}
