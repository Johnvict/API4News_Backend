<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
     public function user(){
        return $this->belongsTo('App\User');
    }

    public function clientNewsReceived() {
        return $this->hasMany('App\ClientNewsReceived');
    }

    public function oldClientNewsReceived() {
        return $this->hasMany('App\OldClientNewsReceived');
    }

    public function subscription() {
        return $this->hasOne('App\Subscription');
    }

    public function invoices() {
        return $this->hasMany('App\Invoice');
    }

    public function payments() {
        return $this->hasMany('App\Payment');
    }
}
