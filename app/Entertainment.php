<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entertainment extends Model
{
    public function country(){
        return $this->belongsTo('App\Country');
    }
    protected $hidden = [
        'country_id', 'created_at', 'updated_at'
    ];
}