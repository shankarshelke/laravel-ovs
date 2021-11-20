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

Route::get('/', function () {
    return view('welcome');
});



Route::get('cache_clear', function () {
	\Artisan::call('cache:clear');
	\Artisan::call('config:cache');
		//  Clears route cache
	\Artisan::call('route:clear');
	\Cache::flush();
	\Artisan::call('optimize');
	exec('composer dump-autoload');
	Cache::flush();
	dd("Cache cleared!");
});

include_once(base_path().'/routes/admin.php');
//include_once(base_path().'/routes/merchant.php');
include_once(base_path().'/routes/front.php');

Auth::routes();