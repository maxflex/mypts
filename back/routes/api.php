<?php

Route::namespace('Api')->group(function () {
    Route::post('auth/login', 'AuthController@login');
    Route::middleware('auth:api')->group(function () {
        Route::get('auth', 'AuthController@getUser');
        Route::get('pts', 'PtsController@index');
        Route::get('entries/autocomplete', 'EntriesController@autocomplete');
        Route::apiResources([
            'entries' => 'EntriesController',
        ]);
    });
});
