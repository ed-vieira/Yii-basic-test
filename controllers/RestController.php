<?php

namespace app\controllers;

use app\controllers\ApiController as Controller;
use yii\filters\auth\HttpBearerAuth;

class RestController extends Controller {

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = ['class' => HttpBearerAuth::class];
        return $behaviors;
    }

}




