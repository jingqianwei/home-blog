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
    return view('file');
});

Auth::routes();

Route::get('/home', 'Home\HomeController@index')->name('home');

// 博客路由
Route::resource('blog', 'Home\BlogController');

// 评论路由
Route::resource('comment', 'Home\CommentController', ['only' => 'store']);

// 上传文件测试接口
Route::post('/file', 'Home\HomeController@redisFile')->name('home.file');

Route::get('/test', 'Home\UserController@test');
