<?php
use Illuminate\Support\Facades\Route;


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
Route::get('/admin', 'Admin\LoginController@login');
Route::get('/admin/login', 'Admin\LoginController@login')->name('login');
Route::post('/admin/dologin', 'Admin\LoginController@authenticate');
Route::get('/admin/forgot', 'Admin\LoginController@forgot');
Route::post('/admin/forgotten', 'Admin\LoginController@forgotten');
Route::post('/admin/resend_otp','Admin\LoginController@resendotp');
Route::post('/admin/checkOTP', 'Admin\LoginController@checkOTP');
Route::get('/admin/resetPassword/{id}', 'Admin\LoginController@resetPassword')->name('resetPassword');
Route::post('/admin/ConfirmPassword', 'Admin\LoginController@ConfirmPassword');
//Route::any('/admin/otp/{id}', 'Admin\LoginController@otp');
Route::any('otp/{id}', [
    'as' => 'otp',
    'uses' => 'Admin\LoginController@showotp'
 ]);
Route::post('/admin/reset', 'Admin\LoginController@reset');
Route::get('/admin/logout', 'Admin\AdminController@getLogout');
Route::get('/admin/error', 'Admin\LoginController@error')->name('error');

Route::group(['middleware' => ['\App\Http\Middleware\AdminAuth'], 'prefix' => 'admin'], function () {
    Route::get('/home', 'Admin\AdminController@dashboard')->name('home');
    Route::get('/dashboard', 'Admin\AdminController@dashboard')->name('dashboard');
    Route::get('/user-management', 'Admin\UserController@index');
    Route::get('/user-detail/{id}', 'Admin\UserController@show');
    Route::post('/user-post-filter', ['uses' => 'Admin\UserController@user_post_filter', 'as' => 'admin.user_post.filter']);
    Route::post('/user/change_user_status', 'Admin\UserController@change_user_status');
    Route::post('/user/filter', [
        'uses' => 'Admin\UserController@filter_list',
        'as' => 'admin.user.filter'
    ]);
    Route::get('/category-management', 'Admin\CategoryController@index');
    Route::get('/subscription-management','Admin\SubscriptionController@Subscription_list');
    Route::post('/subscription/change_status','Admin\SubscriptionController@change_status');
    Route::get('/edit_subscription/{id}','Admin\SubscriptionController@edit_subscription');
    Route::post('/subscription/edit_update/{id}','Admin\SubscriptionController@edit_update');
    // Route::get('editsubscription', function(){
    //     return view('Admin.subscription.edit_subscription');
    // });
    Route::post('subscription/submit','Admin\SubscriptionController@submit');

    Route::get('/ringtone-management','Admin\RingtoneController@Ringtone_list');
    Route::post('/ringtone/submit','Admin\RingtoneController@ringtone_submit');
    Route::post('/ringtone/change_status','Admin\RingtoneController@change_status');
    Route::post('/ringtone-delete','Admin\RingtoneController@delete_ringtone');
    Route::get('/ringtone_edit/{id}','Admin\RingtoneController@edit_ringtone');
    Route::post('/ringtone/update/{id}','Admin\RingtoneController@ringtone_update');

    Route::get('/content-management','Admin\ContentController@Content_list');
    Route::get('/edit_content/{id}','Admin\ContentController@content_edit');
    Route::post('/content/content_update/{id}','Admin\ContentController@update_content');


    
    Route::get('/event-management','Admin\EventController@event_list');
    Route::get('/event-detail/{id}','Admin\EventController@event_detail');
    Route::post('/event-delete','Admin\EventController@event_delete');
    Route::post('/event/change_status','Admin\EventController@change_status');

    Route::get('/poll-management','Admin\PollController@poll_list');
    Route::get('/poll-detail/{id}','Admin\PollController@poll_detail');
    Route::post('/poll-delete','Admin\PollController@poll_delete');
    Route::post('/poll/change_status','Admin\PollController@change_status');
    

    Route::post('category/store', [
        'uses' => 'Admin\CategoryController@store',
        'as' => 'admin.category.store'
    ]);
    Route::get('edit-category/{id}', 'Admin\CategoryController@edit');
    Route::post('category/update/{id}', [
        'uses' => 'Admin\CategoryController@update',
        'as' => 'admin.category.update'
    ]);
    Route::post('/category/change_category_status', 'Admin\CategoryController@change_category_status');
    Route::post('/category/delete', 'Admin\CategoryController@delete_category');
    

    Route::get('/video-management', 'Admin\UserController@videoList');
    Route::get('/video-detail/{id}', 'Admin\UserController@videoDetail');
    Route::post('/video/change_video_status', 'Admin\UserController@change_video_status');
    Route::post('/video/filter', [
        'uses' => 'Admin\UserController@filter_video_list',
        'as' => 'admin.video.filter'
    ]);
    Route::get('/participants/{id}', 'Admin\UserController@participants');
    Route::post('/video/change_participationvideo_status', 'Admin\UserController@change_participationvideo_status');
    Route::get('/report-management', 'Admin\UserController@reportList');
    Route::get('/report-detail/{id}', 'Admin\UserController@reportDetail');
    Route::post('/report/filter', [
        'uses' => 'Admin\UserController@filter_report_list',
        'as' => 'admin.report.filter'
    ]);
    Route::get('/query-management', 'Admin\QueryController@query_list');
    Route::post('/query/filter',[
        'uses'=>'Admin\QueryController@filter',
        'as'=>'admin.query.filter'
    ]);
    Route::get('/manage_subject','Admin\QueryController@manage_subject');
    Route::get('/query-detail/{id}', 'Admin\QueryController@queryDetail');
    Route::post('/subject/submit','Admin\QueryController@subject_submit');
    Route::post('/subject/change_status','Admin\QueryController@change_status');
    Route::get('/edit_subject/{id}','Admin\QueryController@edit_subject');
    Route::post('subject/update/{id}','Admin\QueryController@subject_update');
    Route::post('/query/reply', [
        'uses' => 'Admin\UserController@query_reply',
        'as' => 'admin.query.reply'
    ]);
    
    Route::get('/reason-management', 'Admin\AdminController@reason_list');
    Route::post('reason/store', [
        'uses' => 'Admin\AdminController@reason_store',
        'as' => 'admin.reason.store'
    ]);
    Route::get('edit-reason/{id}', 'Admin\AdminController@edit_reason');
    Route::post('reason/update/{id}', [
        'uses' => 'Admin\AdminController@reason_update',
        'as' => 'admin.reason.update'
    ]);
    Route::post('/reason/change_category_status', 'Admin\AdminController@change_reason_status');
    Route::post('/reason/delete', 'Admin\AdminController@delete_reason');
    Route::any('/custom-notification', 'Admin\AdminController@customNotification');
    Route::get('/notification-management', 'Admin\AdminController@notificationList');
    Route::get('/notification-detail/{id}', 'Admin\AdminController@notificationDetails');

});
