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

// route for authorization pages
Auth::routes();

// redirect default page to /dashboard
Route::get('/', function () {
    return redirect('/admin/dashboard', 301);
});

// admin page related part
Route::group(['prefix' => '/admin', 'middleware' => ['auth', 'forbid-banned-user'], 'namespace' => 'Admin'], function () {
    Route::get('/dashboard', 'MainController@dashboard');
    Route::post('/dashboards/ajax', 'MainController@ajaxHandler');

    // event related
    Route::group(['prefix' => '/content'], function () {
        // event related part
        Route::get('/event', 'ContentManagementController@getEventList');
        Route::get('/event/{id}', 'ContentManagementController@getEvent');
        Route::put('/event/{id}', 'ContentManagementController@saveEvent');
        Route::post('/event/ajax', 'ContentManagementController@ajaxHandler');

        // comment related
        Route::get('/comment', 'CommentManagementController@getCommentList');
        Route::post('/comment/ajax', 'CommentManagementController@ajaxHandler');


        // comment related
        Route::get('/feedback', 'FeedbackManagementController@getFeedbackList');
        Route::post('/feedback/ajax', 'FeedbackManagementController@ajaxHandler');
    });

    // role management
    Route::group(['prefix' => '/role'], function () {
        // admin related part
        Route::get('/admin', 'AdminManagementController@adminList');
        Route::get('/admin/add', 'AdminManagementController@addAdmin');
        Route::post('/admin/add', 'AdminManagementController@doAddAdmin');
        Route::post('/admin/ajax', 'AdminManagementController@ajaxHandler');

        // user related part
        Route::get('/user', 'UserManagementController@userList');
        Route::post('/user/ajax', 'UserManagementController@ajaxHandler');
        Route::get('/user/profile/{id}', 'UserProfileController@getUserProfile');
        Route::post('/user/profile/ajax', 'UserProfileController@ajaxHandler');
    });


    Route::group(['prefix' => '/attachment'], function () {
        Route::get('/image/', 'ImageManagementController@getPage');
        Route::post('/image/ajax', 'ImageManagementController@ajaxHandler');

    });

    Route::group(['prefix' => '/settings'], function () {
        Route::get('/field', 'SystemSettingsController@getFieldDefinition');
        Route::post('/field', 'SystemSettingsController@setFieldDefinition');
        Route::post('/field/ajax', 'SystemSettingsController@ajaxHandler');
    });

});
