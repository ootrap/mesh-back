<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wxmp extends Model
{
        protected $guarded = [
        'authorized', 'user_id', 'token'
    ];

    public function users()
    {
        return $this->belongsToMany('App\User', 'wxmp_user');
    }
}
