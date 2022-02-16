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
Route::post('resendOTP', 'Api\AuthController@resendOTP');
Route::post('login', 'Api\AuthController@login')->name('login');
Route::post('forgotPassword', 'Api\AuthController@forgotPassword');
Route::post('updatePassword', 'Api\AuthController@updatePassword');
Route::get('static-content/about-us', 'Api\StaticContentController@aboutUs');
Route::get('static-content/terms-condition', 'Api\StaticContentController@termsCondition');
Route::get('static-content/privacy-policy', 'Api\StaticContentController@privacyPolicy');
Route::get('ringtones', 'Api\AuthController@ringtones');
Route::get('sendScheduledMsg', 'Controller@sendScheduledMsg');
Route::group(['middleware' => 'auth:api','namespace' => 'Api'], function(){ 
    Route::get('planListing', 'AuthController@planListing');
    Route::post('addFolder', 'AuthController@addFolder');
    // Route::get('myFolders', 'AuthController@myFolders');
    Route::any('addPoll', 'AuthController@addPoll');
    Route::any('myPollList', 'AuthController@myPolls');
    Route::get('allPollList', 'AuthController@allPolls');
    Route::post('sendMyAnswer', 'AuthController@sendMyAnswer');
    Route::post('deletePoll', 'AuthController@deletePoll');
    Route::any('addEvent', 'AuthController@addEvent');
    Route::any('updateEvent', 'AuthController@updateEvent');
    Route::any('myEventList', 'AuthController@myEventList');
    Route::any('allEventList', 'AuthController@allEventList');
    Route::post('deleteEvent', 'AuthController@deleteEvent');
    Route::any('updatePoll', 'AuthController@updatePoll');
    Route::get('myProfile', 'AuthController@myProfile');
    Route::post('editProfile', 'AuthController@editProfile');
    // Route::get('memberProfile/{user_id}', 'AuthController@memberProfile');
    Route::post('secheduleMessageSend', 'AuthController@secheduleMessageSend');
    Route::post('secheduleMessageDelete', 'AuthController@secheduleMessageDelete');
    Route::get('eventDetail/{event_id}', 'AuthController@eventDetail');
    Route::get('pollDetail/{poll_id}', 'AuthController@pollDetail');
    Route::get('searchPoll/{question}','AuthController@searchPoll');
    Route::get('searchEvent/{event}','AuthController@searchEvent');
});  

Route::post('fileUpload','Api\AuthController@fileUpload');

Route::get('directoryfileListing/{directory_id}','Api\AuthController@directoryfileListing');

Route::post('assign_contact_group','Api\AuthController@assign_contact_group');

Route::get('searchFile/{file_name}','Api\AuthController@searchFile');

Route::get('myFolders', 'Api\AuthController@myFolders');
Route::get('memberProfile/{user_id}', 'Api\AuthController@memberProfile');

Route::post('fileDelete','Api\AuthController@fileDelete');
