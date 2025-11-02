<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\NewsObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([NewsObserver::class])]
class News extends Model
{
    protected $fillable = [
        'author_id',
        'news_category_id',
        'title',
        'slug',
        'thumbnail',
        'content',
        'is_featured',
    ];
    
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
    
    public function newsCategory()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }
    
    public function banner()
    {
        return $this->hasOne(Banner::class);
    }
    
    /**
     * Get the full URL for the thumbnail image.
     *
     * @return string|null
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }
        
        return asset('storage/' . $this->thumbnail);
    }
}