<?php

namespace app\controllers;

use app\controllers\RestController as Controller;
use Yii;
use app\services\products\Service;
use Exception;
/**
 */
class CustomerProductsController extends Controller
{   


    protected $service;

    public function init() {
        $this->service = Service::new();
    }


    /**
     * @SWG\Delete(path="/customer/{customer}/products/{id}",
     *     tags={"cliente-produtos"},
     *     summary="Excluir Produto.",
     *     security={{"Bearer":{}}},
     *     @SWG\Parameter(
     *       name="customer",
     *       in="path",
     *       description="ID do Cliente",
     *       required=true,
     *       type="integer", 
     *       @SWG\Items(type="integer"), 
     *       format="int32"
     *     ),
     *     @SWG\Parameter(
     *       name="id",
     *       in="path",
     *       description="identifier",
     *       required=true,
     *       type="integer", 
     *       @SWG\Items(type="integer"), 
     *       format="int32"
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "response",
     *         @SWG\Schema(
     *             @SWG\Property(property="message", type="string")
     *         )
     *     )
     * )
    */
    public function actionDelete() {
        $product = $this->service->find([
            'customer_id' => Yii::$app->request->get('customer'),
            'id' => Yii::$app->request->get('id')
        ])->one();
        if (! is_null($product)) {
            $this->service->destroy($product->id);
            return static::objectResponse(['message' => 'Ok'], 201);
        } else {
            return static::objectResponse(['message' => 'Not Found'], 404);
        }
    }



    /**
     * @SWG\Get(path="/customer/{customer}/products",
     *      tags={"cliente-produtos"},
     *      summary="Listar Produtos.",
     *      security={{"Bearer":{}}},
     *      @SWG\Parameter(
     *        name="customer",
     *        in="path",
     *        description="ID do Cliente",
     *        required=true,
     *        type="integer", 
     *        @SWG\Items(type="integer"), 
     *        format="int32"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "Produtos collection response",
     *         @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Product"))
     *      ),
     * )
    */
    public function actionIndex() {
        $perPage = Yii::$app->request->get('per_page', 10);
        $page = Yii::$app->request->get('page', 1);
        $params['name'] = Yii::$app->request->get('name', '');
        $params['customer_id'] = Yii::$app->request->get('customer');
        return $this->service->paginate($params, $perPage, $page, [
            'created_at' => SORT_DESC,
            'name' => SORT_ASC,
        ]);
    }



    /**
     * @SWG\Get(path="/customer/{customer}/products/{id}",
     *     tags={"cliente-produtos"},
     *     summary="Exibir Produto.",
     *     security={{"Bearer":{}}},
     *     @SWG\Parameter(
     *       name="customer",
     *       in="path",
     *       description="ID do Cliente",
     *       required=true,
     *       type="integer", 
     *       @SWG\Items(type="integer"), 
     *       format="int32"
     *     ),
     *     @SWG\Parameter(
     *       name="id",
     *       in="path",
     *       description="identifier",
     *       required=true,
     *       type="integer", 
     *       @SWG\Items(type="integer"), 
     *       format="int32"
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "Produtos collection response",
     *         @SWG\Schema(ref="#/definitions/Product")
     *     ),
     * )
    */
    public function actionShow()
    {
        $product = $this->service->find([
            'customer_id' => Yii::$app->request->get('customer'),
            'id' => Yii::$app->request->get('id')
        ])->one();
        if (! is_null($product)) {
            return static::objectResponse($product->asArray());
        } else {
            return static::objectResponse(['message' => 'Not Found'], 404);
        }
    }



    /**
     * @SWG\Path(
     *    path="/customer/{customer}/products",
     *    @SWG\Post(
     *      tags={"cliente-produtos"},
     *      summary="Criar Produto.",
     *      security={{"Bearer":{}}},
     *      operationId="store-post-cliente-produto",
     *      @SWG\Parameter(
     *        name="customer",
     *        in="path",
     *        description="ID do Cliente",
     *        required=true,
     *        type="integer", 
     *        @SWG\Items(type="integer"), 
     *        format="int32"
     *      ),
     *      @SWG\Parameter(
     *        description="Nome", 
     *        in="formData",
     *        name="name",
     *        required=true,
     *        type="string",
     *      ),
     *      @SWG\Parameter(
     *        description="Preço", 
     *        in="formData",
     *        name="price",
     *        required=true,
     *        type="integer",
     *      ),
     *      @SWG\Parameter(
     *        description="Foto", 
     *        in="formData",
     *        name="photo",
     *        required=false,
     *        type="file",
     *      ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "Produto response",
     *         @SWG\Schema(ref = "#/definitions/Product")
     *     ),
     *    ),
     *    @SWG\Put(
     *      tags={"cliente-produtos"},
     *      summary="Criar Produto.",
     *      security={{"Bearer":{}}},
     *      operationId="store-put-cliente-produto",
     *      @SWG\Parameter(
     *        name="customer",
     *        in="path",
     *        description="ID do Cliente",
     *        required=true,
     *        type="integer", 
     *        @SWG\Items(type="integer"), 
     *        format="int32"
     *      ),
     *      @SWG\Schema(
     *           @SWG\Property(property="name", type="string"),
     *           @SWG\Property(property="price", type="integer")
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "Produto response",
     *         @SWG\Schema(ref = "#/definitions/Product")
     *      ),
     *    )
     * )
     * 
    */
    public function actionStore()
    {
        try {
            $request = Yii::$app->request;
            if ($request->isPost) {
                $input = Yii::$app->request->post();
            } else {
                $input = Yii::$app->request->getBodyParams();
            }
            $input['customer_id'] = $request->get('customer');
            $product = $this->service->store($input);
            if ($request->isPost) {
                try {
                    $product->handleFormPhoto();
                } catch (Exception $e) {
                    Yii::debug($e->getMessage());
                }
            }
            return static::objectResponse(['message' => "Produto {$product->name} cadastrado com sucesso", 'product' => $product->asArray()], 201);
        } catch(Exception $e) {
            Yii::debug($e->getMessage());
            return static::objectResponse(['message' => "Houve um erro, tente novamente",'product' => []], 500);
        }
    }


    

    /**
     * @SWG\Path(
     *   path="/customer/{customer}/products/{id}",
     *   @SWG\Post(
     *     tags={"cliente-produtos"},
     *     summary="Alterar Produto.",
     *     operationId="update-post-cliente-produto",
     *     @SWG\Parameter(
     *       name="customer",
     *       in="path",
     *       description="ID do Cliente",
     *       required=true,
     *       type="integer", 
     *       @SWG\Items(type="integer"), 
     *       format="int32"
     *     ),
     *     @SWG\Parameter(
     *       name="id",
     *       in="path",
     *       description="identifier",
     *       required=true,
     *       type="integer", 
     *       @SWG\Items(type="integer"), 
     *       format="int32"
     *     ),
     *      security={{"Bearer":{}}},
     *      @SWG\Parameter(
     *        description="Nome", 
     *        in="formData",
     *        name="name",
     *        required=true,
     *        type="string",
     *        @SWG\Schema(type="string"),
     *      ),
     *      @SWG\Parameter(
     *        description="Preço", 
     *        in="formData",
     *        name="price",
     *        required=true,
     *        type="integer",
     *        @SWG\Schema(type="integer"),
     *      ),
     *      @SWG\Parameter(
     *        description="Foto", 
     *        in="formData",
     *        name="photo",
     *        required=true,
     *        type="file",
     *     ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "response",
     *         @SWG\Schema(ref="#/definitions/Product")
     *      ),
     *   ),
     *   @SWG\Put(
     *     tags={"cliente-produtos"},
     *     summary="Alterar Produto.",
     *     operationId="update-put-cliente-produto",
     *     @SWG\Parameter(
     *       name="customer",
     *       in="path",
     *       description="ID do Cliente",
     *       required=true,
     *       type="integer", 
     *       @SWG\Items(type="integer"), 
     *       format="int32"
     *     ),
     *     @SWG\Parameter(
     *       name="id",
     *       in="path",
     *       description="identifier",
     *       required=true,
     *       type="integer", 
     *       @SWG\Items(type="integer"), 
     *       format="int32"
     *     ),
     *      security={{"Bearer":{}}},
     *      @SWG\Schema(
     *           @SWG\Property(property="name", type="string"),
     *           @SWG\Property(property="price", type="integer")
     *      ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "response",
     *         @SWG\Schema(ref="#/definitions/Product")
     *     ),
     *   )
     * )
    */
    public function actionUpdate()
    {
        try {
            $request = Yii::$app->request;
            if ($request->isPost) {
                $input = Yii::$app->request->post();
            } else {
                $input = Yii::$app->request->getBodyParams();
            }
            $old = $this->service->find(['customer_id' => $request->get('customer'), 'id' => $request->get('id')])->one();
            
            if (! is_null($old)) {
                $product = $this->service->update($old->id, $input);
                $message = "Produto {$product->name} alterado com sucesso";    
                if ($request->isPost) {
                    try {
                        $product->handleFormPhoto();
                    } catch (Exception $e) {
                        Yii::debug($e->getMessage());
                    }
                }
                $response['message'] = $message;
                $response['product'] = $product->asArray();
                $code = 201;
            } else {
                $response['message'] = 'Not Found';
                $response['product'] = []; 
                $code = 404;
            }
            return static::objectResponse($response, $code);
        } catch(Exception $e) {
            Yii::debug($e->getMessage());
            $response['message'] = 'Houve um erro, tente novamente';
            $response['product'] = []; 
            return static::objectResponse($response, $code);
        }
    }


}