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

class UserController extends Controller
{
   public function test()
   {
       $user = new Test(54544654);

       print_r($user);
   }
}
