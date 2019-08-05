<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscription;
use App\Subtype;
use Carbon\Carbon;
use App\Country;
use Illuminate\Support\Facades\Auth;

class SubsController extends Controller
{
    public function index($id)
    {
        $sub = Subscription::find($id);
        return $sub;
    }

    public function all()
    {
        $sub = Auth::User()->client->subscription;

        if ($sub != null) {
            $sub->categories = explode("|", $sub->categories);
            $countries = array(explode("|", $sub->countries));
            $sub->expired = new Carbon('today') > $sub->expiry ? true : false;
            foreach ($countries as $country) {
                $sub->countries = Country::find($country);
            }
            $sub->subtype;
        }

        return $sub;
    }

    // We create sub data for a free account -- DEveloper Sub
    public function create(Request $req) {
        $newSub = new Subscription();
        $newSub->client_id = $req->client_id;
        $newSub->subtype_id = $req->subtype_id;
        $newSub->price = 0;
        $newSub->span = $req->span;
        $newSub->countries = $req->countries;
        $newSub->categories = $req->categories;
        $newSub->expiry = $req->expiry;


        $this->deleteUserSubs($req->client_id);

        $newSub->save();
        return $this->returner(201, $this->all());
    }

    public function deleteUserSubs($client_id) {
        $otherSubsToDelete =  Subscription::whereClientId($client_id)->pluck('id');
        if($otherSubsToDelete) {
            Subscription::destroy($otherSubsToDelete);
        }
    }
    // We create SUB FOR A PAID ACC - Standard | Premium
    public function store(Request $request)
    {
        $sub = new Subscription();
        $sub->client_id = $request->client_id;
        $sub->subtype_id = $request->subtype_id;
        $sub->invoice_id = $request->invoice_id;
        $sub->price = $request->price;
        $sub->span = $request->span;
        $sub->countries = $request->countries;
        $sub->categories = $request->categories;
        $sub->expiry =  $request->expiry;

        $this->deleteUserSubs($sub->client_id);
        if ($sub->save()) {
            return (['success' => true]);
            // return $this->returner(201, $this->all());
        } else {
            return (['success' => true]);
        }
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function returner($code, $sub)
    {
        return response()->json(['subscription' => $sub, 'subComplete' => true], $code);
    }
}
