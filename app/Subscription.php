<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $hidden = [
        'updated_at', 'subtype_id'
    ];
    public function client() {
        return $this->belongsTo('App\Client');
    }

    public function subtype() {
        return $this->belongsTo('App\Subtype');
    }
}
