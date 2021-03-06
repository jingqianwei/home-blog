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
