<?php

Route::namespace('Api')->group(function () {
    Route::post('auth/login', 'AuthController@login');
    Route::middleware('auth:api')->group(function () {
        Route::get('auth', 'AuthController@getUser');
        Route::get('pts', 'PtsController@index');
        Route::apiResource('records', 'RecordsController')->only(['index']);
        Route::put('plans/toggle/{plan}', 'PlansController@toggle');
        Route::apiResources([
            'entries' => 'EntriesController',
            'plans' => 'PlansController',
        ]);
    });
});
