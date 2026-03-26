<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Geo extends Model
{

    protected $table = 'api_geo';
    protected $fillable = [
        'address_id',
        'lat',
        'lng',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}