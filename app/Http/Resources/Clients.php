<?php

namespace App\Http\Resources;

use App\Http\Resources\Servicerequests;
use Illuminate\Http\Resources\Json\ResourceCollection;

class Clients extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'services' => $this->services($this)
        ];
    }
    public function services($client) {
        return new Servicerequests($client);
    }
}
