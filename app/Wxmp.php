<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wxmp extends Model
{
        protected $fillable = [
        'authorized', 'user_id', 'token'
    ];

    public function users()
    {
        return $this->belongsTo('App\User');
    }
}
