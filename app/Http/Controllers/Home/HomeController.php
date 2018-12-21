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
        //$this->middleware('auth')->except('index');
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

    /**
     * 上传文件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function file(Request $request)
    {

        dd($_FILES);
        //实例化并获取系统变量传参
        $upload = new \Upload($_FILES['file']['tmp_name'],$_POST['blob_num'],$_POST['total_blob_num'],$_POST['file_name']);
        //调用方法，返回结果
        $upload->apiReturn();

        //上传code文件 $file->getSize()，返回上传文件大小
        if (($request->hasFile('code')) && ($request->file('code')->isValid())) {
            //校验文件大小
            $size = $request->file('code')->getSize();
            if ($size >= 20971520) {
                return $this->json(10003, '文件超过20M大小');
            }

            $fileExtension = $request->file('code')->getClientOriginalExtension();
            $allowFileExtension = ['txt', 'csv', 'zip'];

            // 校验文件后缀
            if (!in_array($fileExtension, $allowFileExtension)) {
                return $this->json(10004, '上传文件的格式不正确');
            }

            //保存到本地
            $fileName = time() . '.' . $fileExtension;
            if (!file_exists(storage_path('custom_uploads'))) {
                mkdir(storage_path('custom_uploads'), 0777);
            }

            //上传文件
            $request->file('code')->move(storage_path('custom_uploads'), $fileName);

            // 检查文件是否存在
            $file = storage_path('custom_uploads') . DIRECTORY_SEPARATOR . $fileName;
            if (!file_exists($file)) {
                return $this->json(10005, '上传文件保存失败！');
            }

            return $this->json(0, '文件上传成功！花费的时间为：' . round(microtime(true) - $str_time, 3) . 's');
        }

        return $this->json(10003, '上传的文件不存在！');
    }

    /**
     * @throws \Exception
     */
    public function redisFile()
    {
        $lines = file_get_contents($_FILES['code']['tmp_name']);//获取文件内容
        ini_set('memory_limit', '-1');//不要限制Mem大小，否则会报错
        $line = explode("\r\n", $lines);//转换成数组
        //实例化redis
        $redis = new \Redis();
        //连接
        $redis->connect('127.0.0.1', 6379);
        $redis->auth('123456'); //密码验证
        $redis->multi();//开启管道, 让效率更高
        if (!$redis) {
            throw new \Exception('redis连接失败！', 1);
        }
        $key = 'batch_key';
        $c = 0;
        $count = count($line);
        for ($i = 0; $i < $count; $i++) {
            $res = $redis->hset($key, $line[$i], 1);
            if ($res) {
                $c++;
            }
        }
        $redis->exec();
    }
}
