<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SubsController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\InvoiceController;
use App\Country;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('api', ['except' => ['login', 'loginAdmin', 'register']]);
        $this->subCtrl = new SubsController();
        $this->dataCtrl = new DataController();
        $this->invoiceCtrl = new InvoiceController();
    }

    // public function loginAdmin(Request $request)
    // {
    //     if ($request->has('password') && $request->has('username')) {
    //         $credentials = $request->only('username', 'password');
    //         $user = User::whereUsername($request->username)->first();
    //         if ($user) {
    //             if ($user->isadmin == true) {
    //                 if ($user->admin != null) {
    //                     if ($token = $this->guard()->attempt($credentials)) {
    //                         return response()->json([
    //                             'loggedIn' => true,
    //                             'token' => $token,
    //                             'userType' => 'admin',
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }


    public function clientLogin(Request $request)
    {
        if ($request->has('password') && $request->has('username')) {
            $credentials = $request->only('username', 'password');
            $user = User::whereUsername($request->username)->first();
            if ($user) {
                if ($token = $this->guard()->attempt($credentials)) {
                    return $this->respondWithUserData($token);
                } else {
                    return response()->json(['error' => 'invalid_credentials', 'loginEvent' => true], 401);
                }
            }
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function me()
    {
        try {
            $user = $this->guard()->user();
            if ($user->isadmin) {
                return response()->json([
                    'checkLogin' => true,
                    'clientStillHere' => true,
                    'user' => $user,
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['checkLogin' => true, 'notHere' => true]);
        }
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json(['logout' => true]);
    }

    public function refresh()
    {
        // return response()->json(['token' => $this->guard()->refresh()], 200);
        return $this->respondWithUserData($this->guard()->refresh());
    }

    protected function respondWithUserData($token)
    {
        $user = Auth::User();
        $clientData = $user->client;
        $invoices = $this->invoiceCtrl->all();
        $sub = $this->subCtrl->all();
        $dataForSub = $this->dataCtrl->dataForSub();

        $client = ([
            'apiKey' => $clientData->apiKey,
            'email' => $user->email,
            'username' => $user->username
        ]);

        return response()->json([
            'success' => true,
            'clientData' => $client,
            'token' => $token,
            'dataForSub' => $dataForSub,
            'subscription' => $sub,
            'invoices' => $invoices
        ]);
    }

    public function guard()
    {
        return Auth::guard();
    }
}
