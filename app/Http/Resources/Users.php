<?php

namespace App\Http\Resources;

use App\Http\Resources\Clients;
use Illuminate\Http\Resources\Json\ResourceCollection;

class Users extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        // return [

        //     // 'id' => $this->id,
        //     // 'email' =>$this->client->email,
        //     // 'name' => $this->client->name,
        //     // 'address' => $this->client->address,
        //     // 'data' =>$this->collection,
        //     // 'services' => $this->client->servicerequest
        // ];
    }

    // public function serviceRequests($user) {
    //     return new Clients($user);
    // }
}
