<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $table = 'api_post';
    protected $fillable = [
        'source_id',
        'user_api_id',
        'title',
        'body',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserApi::class, 'user_api_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }


}