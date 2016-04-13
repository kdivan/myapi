<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['middleware' => 'jwt.auth'], function () {
    Route::resource('film', 'FilmController');
    Route::resource('genre', 'GenreController');

    Route::get('film/getFilmWithGenre/{id}', [
        'as' => 'getFilmWithGenre',
        'uses' => 'FilmController@getFilmWithGenre'
    ]);

    Route::get('genre/getFilmsForGenre/{id}', [
        'as' => 'getFilmsForGenre',
        'uses' => 'GenreController@getFilmsForGenre'
    ]);
});


Route::post('authenticate', [
    'as' => 'authenticate',
    'uses' => 'JWTController@authenticate'
]);

Route::post('hashPassword', [
    'as' => 'hashPassword',
    'uses' => 'JWTController@hashPassword'
]);