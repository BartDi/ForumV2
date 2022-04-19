<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PostController;

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

// Route::get('/', function () {
//     return view('layouts.temp');
// });

Route::controller(PostController::class)->group(function() {
    Route::get('/', 'latestPosts')->name('latest');
    Route::post('/addPost', 'addPost');
    Route::get('/writePost', 'writePost');
    Route::get('/like/post/{id}', 'likePost');
    Route::get('/post/{id}', 'selectPost');
    Route::post('/addComment', 'addComment');
    Route::get('/show/user/{id}', 'userPage');
});

Route::get('/trends', function() {
    return view('post.trends');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
