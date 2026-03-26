<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    protected $table = 'api_album';
    protected $fillable = [
        'source_id',
        'user_api_id',
        'title',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserApi::class, 'user_api_id');
    }

    
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }
    
}