<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class author extends Model
{
    protected $fillable = [
        'user_id',
        'username',
        'avatar',
        'bio',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function news()
    {
        return $this->hasMany(News::class);
    }
    
    /**
     * Get the full URL for the avatar image.
     * Falls back to default profile image if avatar is missing or in unsupported format.
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!$this->avatar) {
            return asset('img/profile.svg');
        }
        
        // Check if file has HEIC extension (not supported by most browsers)
        $extension = strtolower(pathinfo($this->avatar, PATHINFO_EXTENSION));
        if ($extension === 'heic') {
            // Return default avatar for HEIC files until they are converted
            return asset('img/profile.svg');
        }
        
        // Check if file exists in storage
        $filePath = storage_path('app/public/' . $this->avatar);
        if (!file_exists($filePath)) {
            return asset('img/profile.svg');
        }
        
        return asset('storage/' . $this->avatar);
    }
}