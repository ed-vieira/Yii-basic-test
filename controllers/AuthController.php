<?php

namespace app\controllers;

use app\models\forms\UploadForm;
use app\models\User;
use app\controllers\ApiController as Controller;
use Exception;
use Yii;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use yii\web\UnauthorizedHttpException;
/**
 */
class AuthController extends Controller
{   

    /**
     * @SWG\Post(path="/auth/token",
     *     tags={"auth"},
     *     summary="Gerar Token.",
     *     produces={"application/json"},
     *      @SWG\Parameter(
     *        description="Username", 
     *        in="body",
     *        name="body",
     *        required=true,
     *        @SWG\Schema(
     *           @SWG\Property(property="username", type="string"),
     *           @SWG\Property(property="password", type="string")
     *        )
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "response",
     *         @SWG\Schema(
     *           @SWG\Property(property="message", type="string"),
     *           @SWG\Property(property="token", type="string"),
     *        )
     *     ),
     *     @SWG\Response(
     *         response = 401,
     *         description = "response",
     *         @SWG\Schema(
     *           @SWG\Property(property="message", type="string"),
     *        )
     *     ),
     * )
    */
    public function actionToken()
    {
        try {
            $input = Yii::$app->request->getBodyParams();
            $user = User::findByUsername($input['username']);
            if (! is_null($user) && $user->validatePassword($input['password'])) {
                $data['message'] =  'Ok'; 
                $data['token'] =  'Bearer '.$user->generateAccessToken();
                return static::objectResponse($data);
            } else {
                throw new UnauthorizedHttpException('username ou senha inválida', 401);
            }
        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
            return static::objectResponse($data, $e->getCode());
        }
       
    }



    /**
     * @SWG\Post(path="/auth/register",
     *     tags={"auth"},
     *     summary="Criar Novo usuário e gerar token.",
     *     produces={"application/json"},
     *      @SWG\Parameter(
     *        description="User", 
     *        in="body",
     *        name="body",
     *        required=true,
     *        @SWG\Schema(
     *           @SWG\Property(property="name", type="string"),
     *           @SWG\Property(property="email", type="string"),
     *           @SWG\Property(property="username", type="string"),
     *           @SWG\Property(property="password", type="string")
     *        )
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "response",
     *         @SWG\Schema(
     *           @SWG\Property(property="message", type="string"),
     *           @SWG\Property(property="token", type="string"),
     *        )
     *     ),
     *     @SWG\Response(
     *         response = 401,
     *         description = "response",
     *         @SWG\Schema(
     *           @SWG\Property(property="message", type="string"),
     *        )
     *     ),
     * )
    */
    public function actionRegister()
    {
        try {
            $input = Yii::$app->request->getBodyParams();
            $user = User::findByUsername($input['username']);
            if (! is_null($user)) {
                throw new Exception("Username em uso", 500);
            } else {
                $user = new User;
                $user->name = $input['name'];
                $user->email = $input['email'];
                $user->username = $input['username'];
                $user->setPassword($input['password']);
                $user->save();
                $data['message'] =  'Ok'; 
                $data['token'] =  'Bearer '.$user->generateAccessToken();
            }
            return static::objectResponse($data);
        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
            return static::objectResponse($data, $e->getCode());
        }
       
    }



}