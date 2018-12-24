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

        $filename = basename ( $_GET['filename'] ?? 'qr-code.png' );
        header('Content-Type: '.$qrCode->getContentType());
        header ( 'Content-Disposition: attachment; filename="' . $filename . '"' );
        echo $qrCode->writeString();
        exit();

    }
}