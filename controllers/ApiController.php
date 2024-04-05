<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\helpers\Url;
use yii\filters\auth\HttpBearerAuth;

class ApiController extends Controller {

    public static function objectResponse($data=[], int $code = 200) {
        return Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'statusCode' => $code,
            'charset' => 'UTF-8',
            'data' => $data,
        ]);
    }

}




