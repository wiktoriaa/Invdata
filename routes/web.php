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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', function () {
    return view('test');
});



Route::group(['middleware'=>'auth'], function () {

	Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

	Route::get('/', function () {
	    return view('home');
	});

	Route::get('register', function () {
	    return view('home');
	});


	Route::group(['middleware'=>'check-permission:superadmin'], function () {
		Route::get('rooms', 'RoomsController@create')->name('rooms.create');
		Route::post('rooms', 'RoomsController@store')->name('rooms.store');
		Route::delete('rooms', 'RoomsController@delete')->name('rooms.delete');

		Route::get('types', 'TypesController@create')->name('types.create');
		Route::post('types', 'TypesController@store')->name('types.store');
		Route::delete('types', 'TypesController@delete')->name('types.delete');

		Route::delete('scrap', 'ScrapController@delete')->name('scrap.delete');

		Route::get('users', 'UserController@display')->name('users.create');
		Route::post('users', 'UserController@store')->name('users.store');
		Route::put('users', 'UserController@update')->name('users.update');
		Route::delete('users', 'UserController@delete')->name('users.delete');
		
		Route::delete('items', 'ItemsController@delete')->name('items.delete');

		Route::post('delete-item', 'QualityForm@confirm')->name('items.delete-precious');
	});
	
	Route::post('items', 'ItemsController@store')->name('items.store');
	Route::get('items', 'ItemsController@create')->name('items.create');

	Route::get('scrap', 'ScrapController@create')->name('scrap.create');

	Route::get('import', 'ImportItems@create')->name('import.import');
	Route::post('import', 'ImportItems@import')->name('import.store');
});
