<?php

use Illuminate\Support\Facades\Route;
use \App\Models\RolesAndPermissions\Role;
use \App\Models\RolesAndPermissions\Permission;
use \App\Models\User;

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
// 'blanco naranja 
//  naraja 
//  blanco verde 
//  azul
//  blanco azul
//  verde 
//  blanco marron 
//  marron'

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    // $permission = Permission::create([
    // 	'name' => 'Creación de usuarios',
    // 	'slug' => 'create',
    // 	'description' => 'Muestra formulario para la creación de un nuevo usuario en el sistema'
    // ]);
    $rol = Role::find(1);
    $rol->permissions()->sync([1,2]);
     return $rol->with('permissions')->first();
});