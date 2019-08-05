<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('test', function() {
    // http://localhost:8000/api/test?data=Hello&apiKey=myRandomApiKey
    $data = Input::get('data');
    $apiKey = Input::get('apiKey');
    return response()->json(['Data: ' => $data, 'apiKey' => $apiKey]);
});