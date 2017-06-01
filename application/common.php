<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * @param $index_page 当前页
 * @param $total_page 总页数
 *@param  $action  js点击的方法名
 */
function my_page($index_page,$total_page){

    $start = $index_page-5;
    $end = $index_page+4;
    $str = '';
    if($index_page<6){
        $end = $end+6-$index_page;
        $start = 1;
    }
    if($end>$total_page || $total_page-4<$index_page){
        $end = $total_page;
        $start = $total_page-10+1;
    }
    if($total_page<=10){
        $end = $total_page;
        $start = 1;
    }
    if($total_page==0){
        return $str = '';
    }
    $str .= '<li><a class="my_page"   href="javascript:void(0)" name="1">首页</a></li>';
    if($start!= $index_page){
        $str .= '<li><a class="my_page"   href="javascript:void(0)" name="'.($index_page-1).'">上一页</a></li>';
    }
    //循环显示到页面
    for($i=$start;$i<=$end;$i++){
        if($index_page == $i){
            $str .=  '<li class="active"><a class="my_page"   name="'.$i.'" href="javascript:void(0)">'.$i.'</a></li> ';
        }else{
            $str .=  '<li><a class="my_page"   name="'.$i.'" href="javascript:void(0)">'.$i.'</a></li> ';

        }
    }
    if($end != $index_page){
        $str .=  '<li><a class="my_page"   href="javascript:void(0)" name="'.($index_page+1).'">下一页</a></li> ';
    }
    $str .= '<li><a class="my_page"   href="javascript:void(0)" name="'.$total_page.'">末页</a></li>';
    return $str;
}