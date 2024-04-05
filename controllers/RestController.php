<?php

namespace app\controllers;

use Yii;
use app\controllers\ApiController as Controller;
use yii\helpers\Url;
use yii\filters\auth\HttpBearerAuth;

class RestController extends Controller {

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

}




