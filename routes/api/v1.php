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
		Route::post('login', 'AuthController@login');
		Route::post('logout', 'AuthController@logout')->middleware(["auth:api"]);
	});
    
    Route::middleware(["auth:api"])->group(function () {
        // User
        Route::prefix("users")->group(function () {
            Route::get("/", "UserController@index");
            Route::post("/store", "UserController@store");
            Route::get("/{id}", "UserController@show");
            Route::put("/{id}", "UserController@update");
            Route::delete("/{id}", "UserController@delete");
        });
    });
});