<?php

	Route::post('/login', 'UserController@Login');
	Route::get('/logout/{access_token}', 'UserController@Logout');
	Route::post('/register', 'UserController@Register');

	Route::get('/', 'UserController@base');
	Route::get('/users/', 'UserController@index');
	Route::get('/users/user1/user2', 'UserController@index');
	Route::get('/users/user1/{user}', 'UserController@index');
	Route::get('/users/{access_token}', 'UserController@show');
	Route::post('/users', 'UserController@store');
	Route::put('/users/{id}', 'UserController@update');
	Route::delete('/users/{id}', 'UserController@delete');
	
	Route::post('/pretraga', 'UserController@search');
	Route::get('/pretraga/{search}/{offset}/{limit}', 'UserController@search');

