<?php

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', 'SystemStatsController@main');
Route::get('/indexes', 'IndexesController@main');
Route::get('/search', 'SearchController@main');
