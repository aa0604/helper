<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2018/10/3
 * Time: 11:08
 */

namespace xing\helper\controllers;

use Endroid\QrCode\QrCode;

class YiiQrCodeController extends \yii\web\Controller
{

    public function actionEnCode($text)
    {
        $qrCode = new QrCode($text);

        header('Content-Type: '.$qrCode->getContentType());
        echo $qrCode->writeString();
    }

    public function actionEnCodeDownload($text)
    {
        $qrCode = new QrCode($text);

//        header('Content-Type: '.$qrCode->getContentType());

        header("Content-type: application/octet-stream");
        header("Accept-Rangers: bytes");
        header("Content-Disposition: attachment;filename=qr-code.png");
        echo $qrCode->writeString();

    }
}