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

Route::get('/vue', function () {
    return view('vue');
});

Route::get('/time', 'Home\UserController@getMillisecond');


Route::get('/test/db', function () {
    // 使用自定义函数，对应在test数据库中
    $sql = "select id_nextval('order_id') as order_id LIMIT 1";
    $res = DB::select($sql);
    foreach ($res as $val) {
        echo $val->order_id;
    }
});
