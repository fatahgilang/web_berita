<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'news_id',
    ];
    
    /**
     * Get the news that owns the banner.
     */
    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
