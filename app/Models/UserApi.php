<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserApi extends Model
{
    protected $table = 'api_user';
    protected $fillable = [
        'source_id',
        'name',
        'username',
        'email',
        'phone',
        'website',
    ];

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function todos(): HasMany
    {
        return $this->hasMany(Album::class, 'user_api_id');
    }

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class, 'user_api_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_api_id');
    }

    
}