<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newsdata extends Model
{
    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function country(){
        return $this->belongsTo('App\Country');
    }
    protected $hidden = [
        'country_id', 'created_at', 'updated_at'
    ];
    
}
