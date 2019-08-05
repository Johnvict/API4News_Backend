<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subtype extends Model
{
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function subscription() {
        return $this->hasMany('App\Subscription');
    }
    public function invoices() {
        return $this->hasMany('App\Invoice');
    }

}
