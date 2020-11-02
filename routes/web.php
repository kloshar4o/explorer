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


// Deployment WebHook URL
Route::post('/deploy/github', 'DeployController@github');

// App public files
Route::get('/avatars/{avatar}', 'FileAccessController@get_avatar')->name('avatar');
Route::get('/system/{image}', 'FileAccessController@get_system_image');

// Get public thumbnails and files
Route::get('/thumbnail/{name}/public/{token}', 'FileAccessController@get_thumbnail_public');
Route::get('/file/{name}/public/{token}', 'FileAccessController@get_file_public');

// User master,editor,visitor access to image thumbnails and file downloads
Route::group(['middleware' => ['auth:api', 'auth.shared', 'auth.master', 'scope:master,editor,visitor']], function () {
    Route::get('/thumbnail/{name}', 'FileAccessController@get_thumbnail')->name('thumbnail');
    Route::get('/file/{name}', 'FileAccessController@get_file')->name('file');
});

// Admin system tools
Route::group(['middleware' => ['auth:api', 'auth.master', 'auth.admin', 'scope:master'], 'prefix' => 'service'], function () {
    Route::get('/upgrade-database', 'General\UpgradeAppController@upgrade_database');
    Route::get('/down', 'General\UpgradeAppController@down');
    Route::get('/up', 'General\UpgradeAppController@up');
});


// Declare route variables for static pages, but leave them as thery are Vue
Route::get('/contact-us', 'AppFunctionsController@index')->name('ContactUs');
Route::get('/sign-in', 'AppFunctionsController@index')->name('SignIn');
Route::get('/sign-up', 'AppFunctionsController@index')->name('SignUp');
Route::get('/page/{slug}', 'AppFunctionsController@index')->name('DynamicPage');

// Vue route
Route::get('/{any?}', 'AppFunctionsController@index')->where('any', '.*');
