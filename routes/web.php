<?php

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

Route::get('/', 'PageController@index')->name('home');
Route::get('/kanji', 'PageController@kanji')->name('kanji');

Route::prefix('wanikani')->group(function () {
    Route::get('/', 'WaniKaniController@index')->name('wk_summary');
    Route::get('/levels/{level?}', 'WaniKaniController@levels')->name('wk_levels');
});