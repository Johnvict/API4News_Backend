<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function country() {
        return $this->hasMany('App\Country');
    }

    public function newsdata() {
        return $this->hasMany('App\Newsdata');
    }
}
