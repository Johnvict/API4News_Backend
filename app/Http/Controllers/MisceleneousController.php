<?php

namespace App\Http\Controllers;

use App\Country;
use App\Category;
use App\Activecountry;
use Illuminate\Http\Request;

class MisceleneousController extends Controller
{

    public function createCountry(Request $request) {
        $countries = $request->countries;

        foreach ($countries as $country) {
            $newCountry = new Country();
            $newCountry->name = $country['name'];
            $newCountry->code = $country['code'];

            $newCountry->save();
        }

        return response()->json(['success' => true], 200);
    }

    public function createActiveCountry(Request $req)
    {
        foreach ($req->countries as $country) {
            $count = new Country();
            $count->name = $country['name'];
            $count->code = $country['code'];
            $count->save();
        }

        return response()->json(Activecountry::all());
    }

    public function createCategory(Request $request) {
        $categories = $request->categories;

        foreach ($categories as $category) {
            $newCategory = new Category();
            $newCategory->name = $category['name'];

            $newCategory->save();
        }

        return response()->json(['success' => true], 200);
    }
}
