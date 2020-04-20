<?php

Route::namespace('Api')->group(function () {
    Route::post('auth/login', 'AuthController@login');
    Route::middleware('auth:api')->group(function () {
        Route::get('pts', 'PtsController@index');
        Route::apiResource('records', 'RecordsController')->only(['index']);
        Route::put('plans/toggle/{plan}', 'PlansController@toggle');
        Route::put('achievements/achieve/{achievement}', 'AchievementsController@achieve');
        Route::post('rules/apply/{rule}', 'RulesController@apply');
        Route::get('history', 'HistoryController@index');
        Route::apiResources([
            'entries' => 'EntriesController',
            'plans' => 'PlansController',
            'rules' => 'RulesController',
            'achievements' => 'AchievementsController',
        ]);
        Route::prefix('profile')->group(function () {
            Route::get('/', 'ProfileController@get');
            Route::put('/', 'ProfileController@update');
        });
    });
});
