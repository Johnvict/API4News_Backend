<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('welcome');
})->name('homeUrl');


Route::get('news', function() {
    return view('news');
});

//  payment stuffs
Route::post('/pay', 'PaymentController@redirectToGateway')->name('pay');

Route::get('payment/callback', 'PaymentController@handleGatewayCallback');

Route::get('pay/{reference}', [
    'uses' => 'PaymentController@getInvoice',
    'as' => 'pay-invoice'
]);
