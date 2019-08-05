<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/', function () {
    return view('welcome');
})->name('homeUrl');

//Redirect to another url
Route::get('redirector/{any}', function ($any) {
    return redirect('http://redirectToThisUrl.com/' . $any);
});


// Route::get('news', function () {
//     return view('news');
// });

// JSON RESPONSE - GET NEWS BY COUNTRY AND CAEGORY
// Route::get('getNews/country={country}&category={category}&apiKey={apiKey}', [
Route::get('getNews', [
    'as' => 'getNews',
    'uses' => 'ClientController@getNewsApi'
]);
// Route::get('getNewsAsJSON/country={country}&category={category}&apiKey={apiKey}',
Route::get(
    'getNewsAsJSON',
    [
        'as' => 'getNewsAsJSON',
        'uses' => 'NewsAsJsonController@asSpecialRequest'
    ]
);

Route::post('getNews', ['as' => 'getNews', 'uses' => 'ClientController@getNews']);
Route::get('get-test/{id}', ['as' => 'get-test', 'uses' => 'ClientController@clientAccessControl']);

//      for testing purpose
Route::get('allCategoriesButCountry', ['as' => 'allCategoriesButCountry', 'uses' => 'NewsClientController@getClientNews']);
Route::get('test', function () {
    // http://localhost:8000/api/test?data=Hello&apiKey=myRandomApiKey
    $data = Input::get('data');
    $apiKey = Input::get('apiKey');
    return response()->json(['Data: ' => $data, 'apiKey' => $apiKey]);
});
//

Route::post('getNewsByUrl', [
    'as' => 'getNewsByUrl',
    'uses' => 'ClientController@getNewsByUrl'
])->name('getNewsByUrl');

//  MISCELENEOS ACTIVITIES
Route::post('createCountry', ['as' => 'createCountry', 'uses' => 'MisceleneousController@createCountry']);
Route::post('createActiveCountry', ['as' => 'createActiveCountry', 'uses' => 'MisceleneousController@createActiveCountry']);
Route::post('createCategory', ['as' => 'createCategory', 'uses' => 'MisceleneousController@createCategory']);


Route::post('saveNews/{categoryName}', ['as' => 'saveNews', 'uses' => 'NewsfetcherController@saveNews']);
Route::get('loadNews', ['as' => 'loadNews', 'uses' => 'NewsfetcherController@loadAllNews']);

//  OBTAIN TO AVOD DUPICATION
Route::get('getUernamesEmails', ['as' => 'getUernamesEmails', 'uses' => 'UserController@getUsernamesEmailsDomains']);

//CLIENT ACCOUNT
Route::post('createClient', 'AccountController@createClient');
Route::post('loginClient', 'AuthController@clientLogin');
Route::post('refresh', 'AuthController@refresh');
Route::post('logout', 'AuthController@logout');

Route::get('free-plan/country={country}&category={category}&apiKey={apiKey}', [
    'as' => 'getNewsClient',
    'uses' => 'ClientController@getNewsClient'
]);

// CLIENT SUBSCRIPTION OPERATIONS
Route::group(['prefix' => 'sub', 'middleware' => ['jwt.auth']], function () {
    Route::post('create', 'SubsController@store');
    // Route::post('create', 'SubsController@store');
});

// CLIENT PAYMENT OPERATIONS
Route::group(['prefix' => 'invoice', 'middleware' => ['jwt.auth']], function () {
    Route::post('new', 'InvoiceController@store');
    Route::get('get/{id}', 'InvoiceController@index');
    Route::post('modify/{id}', 'InvoiceController@update');
    Route::get('delete/{id}', 'InvoiceController@destroy');
});

// Route::post('regadmin', ['as' => 'admin.add', 'uses' => 'UserController@registerAdmin']);

// ADMIN OPERATIONS
// Route::group(['prefix' => 'admin', 'middleware' => ['jwt.auth']], function () {
//     Route::get('getContactors', ['as' => 'getContactors', 'uses' => 'ClientController@getContactors']);
//     Route::post('replyContact', ['as' => 'replyContact', 'uses' => 'ClientController@replyContact']);
//     Route::get('closeContact/{id}', ['as' => 'closeContact', 'uses' => 'ClientController@closeContact']);

// //  NOTIFICATIONS
// Route::get('getNotifications', ['as' => 'getNotifications', 'uses' => 'NotificationController@getNotifications']);
// Route::get('markReviewRead', ['as' => 'markReviewRead', 'uses' => 'NotificationController@markReviewRead']);
// Route::get('markServiceRead', ['as' => 'markServiceRead', 'uses' => 'NotificationController@markServiceRead']);
// Route::get('markServiceMessageRead/{serviceKey}', ['as' => 'markServiceMessageRead', 'uses' => 'NotificationController@markServiceMessageRead']);
// Route::get('markAffiliateRead', ['as' => 'markAffiliateRead', 'uses' => 'NotificationController@markAffiliateRead']);
// Route::get('markContactRead', ['as' => 'markContactRead', 'uses' => 'NotificationController@markContactRead']);


//  REVIEWS OPERATIONS
//     Route::get('loadReviews', ['as' => 'loadReviews', 'uses' => 'AdminController@loadReviews']);
//     Route::post('approveDisapproveReview', ['as' => 'approveDisapproveReview', 'uses' => 'AdminController@approveDisapproveReview']);
//     Route::post('formatReviewDetail', ['as' => 'formatReviewDetail', 'uses' => 'AdminController@formatReviewDetail']);
//     Route::get('getReview', [
//         'as' => 'getReview', 'uses' => 'HomeController@getReviews'
//     ]);
// });

// CHAT OPERATION FOR CLIENT
// Route::post('getConversationAlone', ['as' => 'getConversationAlone', 'uses' => 'ServiceController@getConversationAlone']);





//  payment stuffs
Route::post('/pay', 'PaymentController@redirectToGateway')->name('pay');

Route::get('payment/callback', 'PaymentController@handleGatewayCallback');

Route::get('pay/{reference}', [
    'uses' => 'PaymentController@getInvoice',
    'as' => 'pay-invoice'
]);

