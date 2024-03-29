<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Models\RolesAndPermissions\Role;
use \App\Models\RolesAndPermissions\Permission;
use \App\Models\User;
use Illuminate\Support\Facades\Gate;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::name("api.v1")->group(function () {

	// Authentication
	Route::prefix("auth")->namespace("Auth")->group(function () {
		Route::post('login', 'AuthController@login');//login
		Route::post('logout', 'AuthController@logout')->middleware(["auth:api"]);//logout
	});
    
    Route::middleware(["auth:api"])->group(function () {
        Route::apiResources([
        'users'       => 'UserController',// User
        'roles'       => 'RoleAndPemission\RoleController',// Roles
        'permissions' => 'RoleAndPemission\PermissionController',// Pemission
        'handbooks'   => 'Handbook\HandbookController',// Handbook
        'subtitles'   => 'Handbook\SubtitleController',// Subtitle
        ]); 
        Route::prefix("images")->namespace("Handbook")->group(function () {// Image
            Route::post('/', 'ImageController@store');// store
            Route::post("/{id}", 'ImageController@update');// update
            Route::delete('/{id}', 'ImageController@destroy');// destroy
        });
        Route::get('/test', function () {
            $user = User::find(13);
            Gate::authorize('access','handbook.index');
            return $user;
        });
    });
});