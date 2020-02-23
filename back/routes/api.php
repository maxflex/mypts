<?php

Route::namespace('Api')->group(function () {
    Route::get('pts', 'PtsController@index');
    Route::get('entries/autocomplete', 'EntriesController@autocomplete');
    Route::apiResources([
        'entries' => 'EntriesController',
    ]);
});
