<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use App\Category;
use App\Subtype;

class DataController extends Controller
{
    public function dataForSub() {
        return [
            'countries' => $this->countries(),
            'categories' => $this->categories(),
            'subTypes' => $this->subtypes()
        ];
    }
    public function countries() {
        return Country::all();
    }

    public function categories() {
        return Category::all();
    }

    public function subtypes() {
        return Subtype::all();
    }

}

