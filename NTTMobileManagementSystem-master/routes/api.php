<?php
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

// synchronization related router
Route::group(['prefix' => '/synchronization', 'namespace' => 'API'], function () {
    Route::post('/ping', 'SyncController@ping');
    Route::post('/add', 'SyncController@add');
});

Route::group(['prefix' => '/event', 'namespace' => 'API', 'middleware' => 'check_ua'], function () {
    Route::get('/', 'EventController@getEventPage');
    Route::get('/date/{year}/{month?}/{day?}', 'EventController@getEventByDate');


    Route::get('/featured', 'EventController@getFeaturedPage');
    Route::get('/popular', 'EventController@getPopularPage');

    Route::get('/{id}', 'EventController@getEvent');
    Route::get('/{id}/comment', 'CommentController@getComment');
    Route::post('/{id}/addComment', 'CommentController@addComment')
        ->middleware('auth.token');
});

Route::group(['prefix' => '/auth', 'namespace' => 'API\Auth', 'middleware' => 'check_ua'], function () {
    Route::post('/renewal', 'TokenRenewController@renew')->middleware('token.renewal');
    Route::post('/login', 'LoginController@authenticate');
    Route::post('/register', 'RegisterController@register');
    Route::post('/resetpassword/request', 'ResetPasswordController@getToken');
    Route::post('/resetpassword/handle', 'ResetPasswordController@handleReset');
});

Route::post('/feedback/add', 'API\FeedbackController@add');
