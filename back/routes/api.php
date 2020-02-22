<?php

Route::namespace('Api')->group(function () {
    Route::get('pts', 'PtsController@index');
    Route::apiResources([
        'entries' => 'EntriesController',
    ]);
});
