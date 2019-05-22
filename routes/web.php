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

// Route::get('/', function () {
//   return File::get(public_path() . '/index.html');
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


//Front Pages
Route::get('/{url}', function($url){
    return view('front/' . $url);
});

//Resource for Pages
Route::resource('pages', 'PageController');

//Resource for Posts
Route::resource('posts', 'PostController');

//Show images/assets from storage
Route::get('storage/{filename}', function ($filename)
{
    $path = storage_path('app/public/logos/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
