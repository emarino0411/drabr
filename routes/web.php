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

Route::get('/', 'ApiController@showPostsByArea');
Route::get('/get-ip-location', 'ApiController@getLocationByIp');
Route::get('/get-posts', 'ApiController@getPosts');
Route::get('/new-post', 'ApiController@newPost');
Route::post('/save-post', 'ApiController@savePost');
Route::get('/save-post', 'ApiController@savePost');
