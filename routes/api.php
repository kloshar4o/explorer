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

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/


// Public routes
Route::group(['middleware' => ['api']], function () {

    // User authentication
    Route::post('/user/check', 'Auth\AuthController@check_account');
    Route::post('/user/register', 'Auth\AuthController@register');
    Route::post('/user/login', 'Auth\AuthController@login');

});

// User master Routes
Route::group(['middleware' => ['auth:api', 'auth.master', 'scope:master']], function () {
    // User
    Route::patch('/user/relationships/settings', 'User\AccountController@update_user_settings');
    Route::patch('/user/profile', 'User\AccountController@update_profile');
    Route::get('/user/invoices', 'User\AccountController@invoices');
    Route::get('/user/storage', 'User\AccountController@storage');
    Route::get('/user', 'User\AccountController@user');
    // Browse
    Route::get('/participant-uploads', 'FileBrowser\BrowseController@participant_uploads');
    Route::get('/file-detail/{unique_id}', 'FileBrowser\BrowseController@file_detail');
    Route::get('/navigation', 'FileBrowser\BrowseController@navigation_tree');
    Route::get('/folders/{unique_id}', 'FileBrowser\BrowseController@folder');
    Route::get('/shared-all', 'FileBrowser\BrowseController@shared');
    Route::get('/latest', 'FileBrowser\BrowseController@latest');
    Route::get('/search', 'FileBrowser\BrowseController@search');
    Route::get('/trash', 'FileBrowser\BrowseController@trash');

    // Trash
    Route::patch('/restore-item/{unique_id}', 'FileFunctions\TrashController@restore');
    Route::delete('/empty-trash', 'FileFunctions\TrashController@clear');

    // Favourites
    Route::delete('/folders/favourites/{unique_id}', 'FileFunctions\FavouriteController@destroy');
    Route::post('/folders/favourites', 'FileFunctions\FavouriteController@store');

    // Auth
    Route::get('/logout', 'Auth\AuthController@logout');
});


// User master,editor routes
Route::group(['middleware' => ['auth:api', 'auth.shared', 'auth.master', 'scope:master,editor']], function () {

    // Edit items
    Route::delete('/remove-item/{unique_id}', 'FileFunctions\EditItemsController@user_delete_item');
    Route::patch('/rename-item/{unique_id}', 'FileFunctions\EditItemsController@user_rename_item');
    Route::post('/create-folder', 'FileFunctions\EditItemsController@user_create_folder');
    Route::post('/create-md-file', 'FileFunctions\EditItemsController@user_create_md_file');
    Route::post('/save-md-file', 'FileFunctions\EditItemsController@user_save_file');
    Route::patch('/move/{unique_id}', 'FileFunctions\EditItemsController@user_move');
    Route::post('/upload', 'FileFunctions\EditItemsController@user_upload');
    Route::get('/file/{file_url}', 'FileFunctions\EditItemsController@convertMarkdown');
});
