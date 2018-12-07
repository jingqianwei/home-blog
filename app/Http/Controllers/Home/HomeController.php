<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * 这里这个构造函数调用了 中间件auth 对我们进行权限认证
     * 即要求我们必须登陆才可以访问该控制器的其他方法
     * 有两种解决方法，一直是在 $this->middleware('auth')->except('你要排除权限认证的方法')，比如 ...->except('index')
     * 另一种是直接干掉这个函数（我们确定这个控制器就只是来展示首页的，那么就干掉它吧）
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
}
