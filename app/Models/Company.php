<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    protected $table = 'api_company';
    protected $fillable = [
        'user_api_id',
        'name',
        'catch_phrase',
        'bs',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserApi::class, 'user_api_id');
    }
}