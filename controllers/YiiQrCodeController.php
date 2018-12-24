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

        $ua = $_SERVER ["HTTP_USER_AGENT"];

        $filename = basename ( $_GET['filename'] ?? 'qr-code.png' );
        $encodedFilename = rawurlencode ( $filename );

        if (preg_match ( "/MSIE/", $ua )) {
            header ( 'Content-Disposition: attachment; filename="' . $encodedFilename . '"' );
        } else if (preg_match ( "/Firefox/", $ua )) {
            header ( "Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"' );
        } else {
            header ( 'Content-Disposition: attachment; filename="' . $filename . '"' );
        }
        header("Content-type: application/octet-stream");
        header("Accept-Rangers: bytes");
        echo $qrCode->writeString();

    }
}