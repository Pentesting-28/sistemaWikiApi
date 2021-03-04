<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
        'users'     => 'UserController',// User
        'roles'     => 'RoleAndPemission\RoleController',// Roles
        'permission' => 'RoleAndPemission\PermissionController',// Pemission
        // 'handbook'  => 'Handbook\HandbookController',// Handbook
        // 'subtitle'  => 'Handbook\SubtitleController',// Subtitle
        // 'image'     => 'Handbook\ImageController'// Image
        ]);
        
    });
});