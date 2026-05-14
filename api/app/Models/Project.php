<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Project extends Model
{
    protected $fillable = ['name', 'slug', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function processingRequests(): HasMany
    {
        return $this->hasMany(ProcessingRequest::class);
    }

    protected static function booted() {
    static::saved(function ($project) {
        Cache::forget('active_projects');
    });

    static::deleted(function ($project) {
        Cache::forget('active_projects');
    });
}

}


