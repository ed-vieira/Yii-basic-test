<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\controllers\RestController as Controller;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
/**
 */
class ProfileController extends Controller
{   


    /**
     * @SWG\Get(path="/profile/user",
     *     tags={"auth"},
     *     summary="User",
     *     security={{"Bearer":{}}},
     *     @SWG\Response(
     *         response = 200,
     *         description = "response",
     *         @SWG\Schema(ref="#/definitions/User")
     *     )
     * )
    */
    public function actionUser()
    {
        $identity = Yii::$app->user->identity;
        return static::objectResponse([
           'name' => $identity->name,
           'email' => $identity->email,
           'username' => $identity->username,
        ]);
    }


    /**
     * @SWG\Post(path="/profile/logout",
     *     tags={"auth"},
     *     summary="Logout.",
     *     security={{"Bearer":{}}},
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response = 200,
     *         description = "response",
     *         @SWG\Schema(
     *           @SWG\Property(property="message", type="string"),
     *        )
     *     )
     * )
    */
    public function actionLogout()
    {
        $user = User::findOne(Yii::$app->user->id);
        if (! is_null($user)) {
            $user->access_token = null;
            $user->save();    
        }
        return static::objectResponse(['message' => 'Token Invalidated']);
    }


}