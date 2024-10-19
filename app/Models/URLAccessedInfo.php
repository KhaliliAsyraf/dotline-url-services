<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class URLAccessedInfo extends Model
{
    use HasFactory;

    protected $table = 'url_accessed_infos';
    protected $guarded = [];

    public function getAccessedTimeAttribute(): string
    {
        return $this->created_at;
    }
}
