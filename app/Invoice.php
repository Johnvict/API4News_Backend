<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function client() {
        return $this->belongsTo('App\Client');
    }
    public function subtype() {
        return $this->belongsTo('App\Subtype');
    }
    public function payment() {
        return $this->hasOne('App\Payment');
    }
}
