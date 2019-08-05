<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function invoice() {
        return $this->belongsTo('App\Invoice');
    }
    public function client() {
        return $this->belongsTo('App\Client');
    }
}
