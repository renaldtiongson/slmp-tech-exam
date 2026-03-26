<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Address extends Model
{

    protected $table = 'api_address';
    protected $fillable = [
        'user_api_id',
        'street',
        'suite',
        'city',
        'zipcode',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserApi::class, 'user_api_id');
    }

    public function geo(): HasOne
    {
        return $this->hasOne(Geo::class);
    }
}