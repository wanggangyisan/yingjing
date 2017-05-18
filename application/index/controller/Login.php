<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/14
 * Time: 11:15
 */

namespace app\index\controller;


use phpqrcode\QRcode;
use think\Controller;

class Login extends Controller
{
    public function login()
    {
        $qrcode = $this->qrcode();
        echo $qrcode;
    }
    /*生成二维码*/
    public function qrcode()
    {
        vendor("phpqrcode.phpqrcode");
        $value = 'http://192.168.6.166/index/login/login';
        $path = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],'/'));
        $logo = $path.'/public/static/images/seal_zcdd.png';
        $qrcode= $path.'/public/static/images/qrcode.png';
        $path_new_code = '/public/static/images/new_qrcode.png';
        $new_code= $path.$path_new_code;
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 10;//生成图片大小
        //生成二维码图片
        QRcode::png($value, $qrcode, $errorCorrectionLevel, $matrixPointSize, 2);
        if ($logo !== FALSE) {
            $qrcode = imagecreatefromstring(file_get_contents($qrcode));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $qrcode_width = imagesx($qrcode);//二维码图片宽度
            $qrcode_height = imagesy($qrcode);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $qrcode_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($qrcode_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($qrcode, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
        }
        imagepng($qrcode,$new_code);
        return  $path_new_code;
    }
}