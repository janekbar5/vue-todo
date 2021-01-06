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


//Auth::routes(['verify' => true]);
Auth::routes();


Route::prefix('v1')->group(function () {   
    
    Route::prefix('auth')->group(function () {
        // routes are public
        
        Route::post('register', 'AuthController@register');      
        Route::post('login', 'AuthController@login')->name('verification.verify');       
        Route::get('refresh', 'AuthController@refresh');      
        Route::get('login', 'AuthController@verify');
        
        
        // routes for the authenticated users
        Route::middleware('auth:api')->group(function () {            
            Route::get('user', 'AuthController@user'); 
            Route::get('todos', 'TodoController@index');
            Route::post('todos/create', 'TodoController@store');
            Route::put('todos/update/{id}', 'TodoController@update');
            Route::delete('todos/delete/{id}', 'TodoController@destroy');
            Route::post('logout', 'AuthController@logout');
        });
    });

    
    Route::middleware('auth:api')->group(function () {
        Route::resource('user', 'UserController')->only(['index','show']);
    });
});