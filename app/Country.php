<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    // public function category(){
    //     return $this->belongsTo('App\Category');
    // }

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function newsdata() {
        return $this->hasMany('App\Newsdata');
    }
    public function business() {
        return $this->hasMany('App\Business');
    }
    public function entertainment() {
        return $this->hasMany('App\Entertainment');
    }
    public function health() {
        return $this->hasMany('App\Health');
    }
    public function science() {
        return $this->hasMany('App\Science');
    }
    public function sport() {
        return $this->hasMany('App\Sport');
    }
    public function technology() {
        return $this->hasMany('App\Technology');
    }
}
