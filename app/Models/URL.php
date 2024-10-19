<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class URL extends Model
{
    protected $table = 'urls';
    protected $guarded = [];
    
    /**
     * accessedURLInfo
     *
     * @return HasMany
     */
    public function accessedURLInfo(): HasMany
    {
        return $this->hasMany(URLAccessedInfo::class, 'id_urls', 'id');
    }
    
    /**
     * scopeWhereShortURL
     *
     * @param  Builder $query
     * @param  string $url
     * @return Builder
     */
    public function scopeWhereShortURL(Builder $query, string $url): Builder
    {
        return $query->where('shorten_url', $url);
    }
    
    /**
     * getFullShortenURLAttribute
     *
     * @return string
     */
    public function getFullShortenURLAttribute(): string
    {
        return route('redirect', ['url' => $this->shorten_url]);
    }
}
