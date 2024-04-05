<?php

namespace app\controllers;

use Yii;
use app\services\products\Service;
use app\controllers\RestController as Controller;
use Exception;

/**
 */
class ProductsController extends Controller
{   


    protected $service;

    public function init() {
        $this->service = Service::new();
    }


    /**
     * @SWG\Delete(path="/products/{id}",
     *     tags={"produtos"},
     *     summary="Excluir Produto.",
     *     security={{"Bearer":{}}},
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
     *         @SWG\Schema(ref="#/definitions/Product")
     *     )
     * )
    */
    public function actionDelete() {
        $ok = $this->service->destroy(Yii::$app->request->get('id'));
        return static::objectResponse(['message' => $ok? 'Ok': 'Not Found']);
    }



    /**
     * @SWG\Get(path="/products",
     *     tags={"produtos"},
     *     summary="Listar Produtos.",
     *      security={{"Bearer":{}}},
     *      @SWG\Parameter(
     *       name="name",
     *       in="query",
     *       description="Buscar por Nome do Produto",
     *       required=false,
     *       type="string",
     *     ),
     *     @SWG\Parameter(
     *        name="customer_id",
     *        in="query",
     *        description="Buscar por ID do Cliente",
     *        required=false,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="cpf",
     *        in="query",
     *        description="Buscar por CPF do Cliente",
     *        required=false,
     *        type="string",
     *     ),
     *     @SWG\Response(
     *         response= 200,
     *         description= "Produtos collection response",
     *         @SWG\Schema(type="array", @SWG\Items(ref = "#/definitions/Product"))
     *     ),
     * )
    */
    public function actionIndex() {
       $perPage = Yii::$app->request->get('per_page', 10);
       $page = Yii::$app->request->get('page', 1);
       $params['name'] = Yii::$app->request->get('name', '');
       $params['customer_id'] = Yii::$app->request->get('customer_id', '');
       $params['cpf'] = Yii::$app->request->get('cpf', '');
       return $this->service->paginate($params, $perPage, $page, [
           'created_at' => SORT_DESC,
           'name' => SORT_ASC,
       ]);
    }



    /**
     * @SWG\Get(path="/products/{id}",
     *     tags={"produtos"},
     *     summary="Exibir Produto.",
     *     security={{"Bearer":{}}},
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
    public function actionShow() {
        $product = $this->service->findById(Yii::$app->request->get('id'));
        return static::objectResponse($product->asArray());
    }



    /**
     * @SWG\Path(
     *   path="/products",
     *   @SWG\Post(
     *      tags={"produtos"},
     *      summary="Criar Produto.",
     *      security={{"Bearer":{}}},
     *      consumes={"multipart/form-data"},
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
     *        description="ID do Cliente", 
     *        in="formData",
     *        name="customer_id",
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
     *   ),
     *   @SWG\Put(
     *      tags={"produtos"},
     *      summary="Criar Produto.",
     *      security={{"Bearer":{}}},
     *      consumes={"application/json"},
     *      @SWG\Parameter(
     *        description="Body", 
     *        in="body",
     *        name="body",
     *        required=true,
     *        type="string",
     *        @SWG\Schema(
     *           @SWG\Property(property="name", type="string"),
     *           @SWG\Property(property="price", type="integer")
     *        )
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "response",
     *         @SWG\Schema(ref="#/definitions/Product")
     *      ),
     *   )
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
            $product = $this->service->store($input);
            if ($request->isPost) {
                try {
                    $product->handleFormPhoto();
                } catch (Exception $e) {
                    Yii::debug($e->getMessage());
                }
            }
            return static::objectResponse(['message' => "Produto {$product->name} cadastrado com sucesso", 'product' => $product->asArray()]);
        } catch (Exception $e) {
            Yii::debug($e->getMessage());
            return static::objectResponse(['message' => 'Algo deu errado, tente novamente', 'product' => []]);
        }
    }

    

    /**
     * @SWG\Path(
     *    path="/products/{id}",
     *    @SWG\Post(
     *     tags={"produtos"},
     *     summary="Alterar Produto.",
     *     consumes={"application/json"},
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="identifier",
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
     *        description="ID do Cliente", 
     *        in="formData",
     *        name="customer_id",
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
     *      @SWG\Response(
     *         response = 200,
     *         description = "response",
     *         @SWG\Schema(ref="#/definitions/Product")
     *       ),
     *    ),
     *    @SWG\Put(
     *     tags={"produtos"},
     *     summary="Alterar Produto.",
     *     consumes={"application/json"},
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
     *        description="Body", 
     *        in="body",
     *        name="body",
     *        required=true,
     *        type="string",
     *        @SWG\Schema(
     *           @SWG\Property(property="name", type="string"),
     *           @SWG\Property(property="price", type="integer")
     *        )
     *      ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "response",
     *         @SWG\Schema(ref="#/definitions/Product")
     *     ),
     *    ),
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
            $product = $this->service->update($request->get('id'), $input);
            if ($request->isPost) {
                try {
                    $product->handleFormPhoto();
                } catch (Exception $e) {
                    Yii::debug($e->getMessage());
                }
            }
            return static::objectResponse(['message' => "Produto {$product->name} alterado com sucesso", 'product' => $product->asArray()]);
        } catch (Exception $e) {
            Yii::debug($e->getMessage());
            return static::objectResponse(['message' => 'Algo deu errado, tente novamente', 'product' => []]);
        }
    }


}