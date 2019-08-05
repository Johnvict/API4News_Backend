<?php

namespace App\Http\Controllers;

use App\User;
use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;


class AccountController extends Controller
{
    public function createClient(Request $request) {
        $clientDuplicate = Client::where(['domain' => $request->domain])->first();
        $userDuplicate = User::where(['username' => $request->username])->first();
        $userDuplicate2 = User::where(['email' => $request->email])->first();

        if ($clientDuplicate != null || $userDuplicate !== null || $userDuplicate2 !== null) {
            return response()->json(['duplicate' => true]);
        }

        $newUser =  new User();
        $newUser->username = $request->username;
        $newUser->password = bcrypt($request->password);
        $newUser->email = $request->email;
        $newUser->isAdmin = false;

        if ($newUser->save()) {
            // Get user Id
            $userId = User::whereUsername($request->username)->first()->id;

            $newClient = new Client();
            $newClient->user_id = $userId;
            $newClient->apiKey = $request->apiKey;
            $newClient->domain = $request->domain;
            // $newClient->plan = $request->plan == null ? 'null' : $request->plan;
            $newClient->active = true;

            if ($newClient->save()) {
                $authCtrl = new AuthController();
                $req = new Request([
                    'username' => $request->username,
                    'password' => $request->password
                ]);
                return $authCtrl->clientLogin($req);
                // return response()->json(['success' => true, 'clientData' => $client, 'token' => $token]);
            }
        }
        return response()->json(['success' => false]);
    }
}
