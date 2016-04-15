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


Route::group(['middleware' => ['jwt.auth']], function () {
    Route::group(['middleware' => ['roles:ROLE_CRUD']], function () {
        Route::resource('film', 'FilmController');
        Route::resource('genre', 'GenreController');
        Route::resource('seance', 'SeanceController');
        Route::resource('salle', 'SalleController');
        Route::resource('personne', 'PersonneController');
        Route::resource('distributeur', 'DistributeurController');
        Route::resource('fonction', 'FonctionController');
        Route::resource('forfait', 'ForfaitController');
        Route::resource('membre', 'MembreController');
        Route::resource('reduction', 'ReductionController');
        Route::resource('abonnement', 'AbonnementController');
        Route::resource('historiqueMembre', 'HistoriqueMembreController');

        Route::get('film/getFilmWithGenre/{id}', [
            'as' => 'getFilmWithGenre',
            'uses' => 'FilmController@getFilmWithGenre'
        ]);

        Route::get('genre/getFilmsForGenre/{id}', [
            'as' => 'getFilmsForGenre',
            'uses' => 'GenreController@getFilmsForGenre'
        ]);
    });

    Route::group(['middleware' => ['roles:ROLE_READONLY']], function () {
        Route::get('genre', 'GenreController@index');
        Route::get('genre/{genre}', 'GenreController@show');
        Route::get('film', 'FilmController@index');
        Route::get('film/{film}', 'FilmController@show');
        Route::get('seance', 'SeanceController@index');
        Route::get('seance/{seance}', 'SeanceController@show');
        Route::get('salle', 'SalleController@index');
        Route::get('salle/{salle}', 'SalleController@show');
        Route::get('personne', 'PersonneController@index');
        Route::get('personne/{personne}', 'PersonneController@show');
        Route::get('distributeur', 'DistributeurController@index');
        Route::get('distributeur/{distributeur}', 'DistributeurController@show');
        Route::get('fonction', 'FonctionController@index');
        Route::get('fonction/{fonction}', 'FonctionController@show');
        Route::get('forfait', 'ForfaitController@index');
        Route::get('forfait/{forfait}', 'ForfaitController@show');
        Route::get('reduction', 'ReductionController@index');
        Route::get('reduction/{reduction}', 'ReductionController@show');
        Route::get('abonnement', 'AbonnementController@index');
        Route::get('abonnement/{abonnement}', 'AbonnementController@show');
        Route::get('historiqueMembre', 'HistoriqueMembreController@index');
        Route::get('historiqueMembre/{historiqueMembre}', 'HistoriqueMembreController@show');

        Route::get('film/getFilmWithGenre/{id}', [
            'as' => 'getFilmWithGenre',
            'uses' => 'FilmController@getFilmWithGenre'
        ]);

        Route::get('genre/getFilmsForGenre/{id}', [
            'as' => 'getFilmsForGenre',
            'uses' => 'GenreController@getFilmsForGenre'
        ]);
    });

});

Route::post('authenticate', [
    'as' => 'authenticate',
    'uses' => 'JWTController@authenticate'
]);

Route::post('hashPassword', [
    'as' => 'hashPassword',
    'uses' => 'JWTController@hashPassword'
]);