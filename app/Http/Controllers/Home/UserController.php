<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2018/12/21
 * Time: 13:51
 */

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Database\Schema\Blueprint;

class UserController extends Controller
{
    public function test()
    {
        $arr = [
            'appkey' => 111,
            'timestamp' => 222,
            'sign' => 333,
        ];
//        $userId=$this->create_uuid();
//        $user = new Test($userId);
//        $data=array('id'=>$userId,'name'=>'大明'.$userId,'password'=>'14e1b600b1fd579f47433b88e8d85291','sex'=>'男');
//        if($result=$user->insert($data)){
//            echo '插入成功：','<pre/>';
//            print_r($result);
//        }
//        exit;
//
//        $condition=array("id"=>$userId);
//        $list=$user->select($condition);
//        if($list) {
//            echo '查询成功：', '<pre/>';
//            print_r($list);
//        }
        for ($i = 0; $i < 10; $i++) {//10个库
            $sql = "drop database library_{$i};";//删库 谨慎
           \DB::statement($sql);
            $sql = "create database library_{$i} default character set utf8mb4 collate utf8mb4_unicode_ci;";
            \DB::statement($sql);

            for($j=0;$j<10;$j++){		//10个表
                $sql="drop table if exists user_{$j};";
                \DB::statement($sql);
                // 创建数据表
                \Schema::create('user_' . $j, function (Blueprint $table) {
                    $table->char('id', 36);
                    $table->char('name', 15)->default('');
                    $table->char('password', 32)->default('');
                    $table->char('sex', 1)->default('男');
                    $table->primary('id');
                    $table->engine = 'InnoDB';
                });
            }
        }
    }

    public function getMillisecond()
    {
        $arr = ['php', 'java', 'python', 'go'];
        $res = array_flip($arr);

        dd($res);
        $str = microtime(); // 不加true返回字符串，微秒 秒
        list($s1, $s2) = explode(' ', $str);
        $second = (float)$s1 + (float)$s2; // 返回的是，秒(小数)，秒(整数)
        $millisecond = $second * 1000; // 把当前的秒转化为毫秒，结果还是浮点型带一位小数
        // 把浮点型写入字符串中且让小数点的长度为0，及舍去小数点后的值
        //sprintf('%.0f', $millisecond); 会遵循小数部分的四舍五入
        dd((int)$millisecond);

        list($s1, $s2) = explode(' ', microtime());

        return (int)((floatval($s1) + floatval($s2)) * 1000);
    }


    //生成唯一uuid
    public function create_uuid($prefix = "")
    {    //可以指定前缀
        $str = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8) . '-';
        $uuid .= substr($str, 8, 4) . '-';
        $uuid .= substr($str, 12, 4) . '-';
        $uuid .= substr($str, 16, 4) . '-';
        $uuid .= substr($str, 20, 12);
        return $prefix . $uuid;
    }
}
