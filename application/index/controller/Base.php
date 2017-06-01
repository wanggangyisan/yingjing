<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 9:29
 */

namespace app\index\controller;


use think\Controller;
use think\Request;

class Base extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        if(session("user")==null){
            $this->redirect('/');
        }
    }
}