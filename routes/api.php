<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

/* Set API Lang */
\App::setlocale(!empty(request()->header('Lang')) ? request()->header('Lang') : 'en');

/*Services Without token*/
Route::post('register', 'Api\AuthController@register');
Route::post('otp', 'Api\AuthController@otp');

Route::any('request_otp', 'Api\AuthController@requestOtpadmin');

Route::post('resendOTP', 'Api\AuthController@resendOTP');
Route::post('login', 'Api\AuthController@login')->name('login');
Route::post('forgotPassword', 'Api\AuthController@forgotPassword');
Route::post('updatePassword', 'Api\AuthController@updatePassword');
Route::get('static-content/about-us', 'Api\StaticContentController@aboutUs');
Route::get('static-content/terms-condition', 'Api\StaticContentController@termsCondition');
Route::get('static-content/privacy-policy', 'Api\StaticContentController@privacyPolicy');

Route::group(['middleware' => 'auth:api','namespace' => 'Api'], function(){ 
    Route::get('planListing', 'AuthController@planListing');
    Route::post('addFolder', 'AuthController@addFolder');
    Route::get('myFolders', 'AuthController@myFolders');
    Route::post('addPoll', 'AuthController@addPoll');
    Route::get('myPollList', 'AuthController@myPolls');
    Route::get('allPollList', 'AuthController@allPolls');
    Route::post('sendMyAnswer', 'AuthController@sendMyAnswer');
    Route::post('deletePoll', 'AuthController@deletePoll');
    Route::post('addEvent', 'AuthController@addEvent');
    Route::get('myEventList', 'AuthController@myEventList');
    Route::get('allEventList', 'AuthController@allEventList');
});    
