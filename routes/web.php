<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['prefix'=>'blog'],function(){
	Route::get('/',[App\Http\Controllers\BlogController::class, 'get_blog'])->name('get_blog');
	Route::group(['middleware'=>'auth'],function(){
		Route::get('/add',[App\Http\Controllers\BlogController::class, 'blog_add_form'])->name('blog_add_form');
		Route::post('/add',[App\Http\Controllers\BlogController::class, 'blog_add'])->name('blog_add');
		Route::get('/edit/{id}',[App\Http\Controllers\BlogController::class, 'blog_edit_form'])->name('blog_edit_form');
		Route::post('/edit',[App\Http\Controllers\BlogController::class, 'blog_edit'])->name('blog_edit');
		Route::get('/status/{id}',[App\Http\Controllers\BlogController::class, 'blog_status_update'])->name('blog_status_update');

		Route::get('/delete/{id}',[App\Http\Controllers\BlogController::class, 'blog_delete'])->name('blog_delete');
	});
	
});
