<?php

namespace App\Http\Resources;

use App\Servicerequest;
use Illuminate\Http\Resources\Json\ResourceCollection;

class Servicerequests extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    // public function toArray($request)
    // {
    //     // return parent::toArray($request);
    //     return [
    //         'attachedFiles' => $this->serviceFile($this),
    //         // 'deliveryData' => $this->servicedelivery()
    //     ];
    // }
    // public function serviceFile($id) {
    //     return Servicerequest::find($id)->servicefiles->get();
    // }

}
