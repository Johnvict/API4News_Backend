<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OldClientNewsReceived extends Model
{
    public function client(){
        return $this->hasOne('App\Client');
    }
}
