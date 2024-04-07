<?php

namespace app\controllers;

use app\models\Customer;
use app\controllers\RestController as Controller;
use app\models\CustomerAddress;
use yii\data\ActiveDataProvider;
use Exception;
use app\utils\strings\Handler as StrHandler;
use app\services\customers\Service;
use Yii;
use yii\helpers\Url;

class CustomersController extends Controller
{
    
    protected $service;


    public function init() {
        $this->service = Service::new();
    }

    /**
     * @SWG\Delete(path="/customers/{id}",
     *     tags={"clientes"},
     *     summary="Excluir Cliente.",
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
     *         @SWG\Schema(
     *           @SWG\Property(property="message", type="string")
     *         )
     *     )
     * )
    */
    public function actionDelete() {
        $ok = $this->service->destroy(Yii::$app->request->get('id'));
        return static::objectResponse(['message' => $ok? 'Ok' : 'Not Found']);
    }

    /**
     * @SWG\Get(path="/customers",
     *     tags={"clientes"},
     *     summary="Listar Clientes.",
     *     security={{"Bearer":{}}},
     *     @SWG\Parameter(
     *       name="name",
     *       in="query",
     *       description="Buscar por Nome",
     *       required=false,
     *       type="string",
     *     ),
     *     @SWG\Parameter(
     *        name="cpf",
     *        in="query",
     *        description="Buscar por CPF",
     *        required=false,
     *        type="string",
     *     ),
     *     @SWG\Parameter(
     *       name="per_page",
     *       in="query",
     *       description="Items por página",
     *       required=false,
     *       type="integer", 
     *       @SWG\Items(type="integer"), 
     *       format="int32"
     *     ),
     *     @SWG\Parameter(
     *       name="page",
     *       in="query",
     *       description="página",
     *       required=false,
     *       type="integer", 
     *       @SWG\Items(type="integer"), 
     *       format="int32"
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "Cliente response",
     *         @SWG\Schema(ref="#/definitions/Customer")
     *     ),
     * )
    */
    public function actionIndex()
    {
        $perPage = Yii::$app->request->get('per_page', 10);
        $page = Yii::$app->request->get('page', 1);
        $name = Yii::$app->request->get('name', '');
        $cpf = Yii::$app->request->get('cpf', '');
        return $this->service->paginate(['name' => $name, 'cpf' => $cpf], $perPage, $page, [
            'created_at' => SORT_DESC,
            'name' => SORT_ASC,
        ]);
    }

    /**
     * @SWG\Get(path="/customers/{id}",
     *     tags={"clientes"},
     *     summary="Exibir Cliente.",
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
     *         description = "Cliente response",
     *         @SWG\Schema(ref="#/definitions/Customer")
     *     ),
     * )
    */
    public function actionShow() {   
        $customer = $this->service->findById(Yii::$app->request->get('id'));
        return static::objectResponse($customer->asArray());
    }


    /**
     * 
     * @SWG\Path(
     *   path="/customers",
     *   @SWG\Post(
     *      tags={"clientes"},
     *      summary="Criar Cliente.",
     *      consumes={"multipart/form-data"},
     *      produces={"application/json"},
     *      operationId="storeCustomerForm",
     *      security={{"Bearer":{}}},
     *      @SWG\Parameter(
     *        description="Nome", 
     *        in="formData",
     *        name="name",
     *        required=true,
     *        type="string",
     *      ),
     *      @SWG\Parameter(
     *        description="CPF", 
     *        in="formData",
     *        name="cpf",
     *        required=true,
     *        type="string",
     *        minLength=11,
     *        maxLength=14,
     *      ),
     *      @SWG\Parameter(
     *        description="Sexo", 
     *        in="formData",
     *        name="sex",
     *        required=true,
     *        type="string",
     *        maxLength=1,
     *        minLength=1,
     *      ),
     *      @SWG\Parameter(
     *        description="Foto", 
     *        in="formData",
     *        name="photo",
     *        required=false,
     *        type="file",
     *      ),
     *      @SWG\Parameter(
     *        description="CEP", 
     *        in="formData",
     *        name="cep",
     *        required=true,
     *        type="string",
     *        maxLength=12,
     *      ),
     *      @SWG\Parameter(
     *        description="uf", 
     *        in="formData",
     *        name="uf",
     *        required=true,
     *        type="string",
     *        maxLength=2,
     *      ),
     *      @SWG\Parameter(
     *        description="Cidade", 
     *        in="formData",
     *        name="cidade",
     *        required=true,
     *        type="string",
     *        maxLength=60,
     *      ),
     *      @SWG\Parameter(
     *        description="Rua", 
     *        in="formData",
     *        name="rua",
     *        required=true,
     *        type="string",
     *        maxLength=100,
     *      ),
     *      @SWG\Parameter(
     *        description="Número", 
     *        in="formData",
     *        name="numero",
     *        required=true,
     *        type="string",
     *        maxLength=20,
     *      ),
     *      @SWG\Parameter(
     *        description="Complemento", 
     *        in="formData",
     *        name="complemento",
     *        required=false,
     *        type="string",
     *        maxLength=200,
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "response",
     *         @SWG\Schema(ref="#/definitions/Customer")
     *     ),
     *   ),
     *   @SWG\Put(
     *      tags={"clientes"},
     *      summary="Criar Cliente.",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *      operationId="storeCustomerPut",
     *      security={{"Bearer":{}}},
     *      @SWG\Parameter(
     *        description="Body", 
     *        in="body",
     *        name="body",
     *        required=true,
     *        @SWG\Schema(
     *           @SWG\Property(property="name", type="string"),
     *           @SWG\Property(property="cpf", type="string"),
     *           @SWG\Property(property="sex", type="string"),
     *           @SWG\Property(property="cep", type="string"),
     *           @SWG\Property(property="uf", type="string"),
     *           @SWG\Property(property="cidade", type="string"),
     *           @SWG\Property(property="rua", type="string"),
     *           @SWG\Property(property="numero", type="string"),
     *           @SWG\Property(property="complemento", type="string"),
     *        )
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "response",
     *         @SWG\Schema(ref="#/definitions/Customer")
     *     ),
     *   )
     * )
     * 
     * 
    */
    public function actionStore()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $input = Yii::$app->request->post();
        } else {
            $input = Yii::$app->request->getBodyParams();
        }

        $oldCustomer = $this->service->find(['cpf_number' => StrHandler::digits($input['cpf'])])->one();
            
        if (is_null($oldCustomer)) {
            try {
                $customer = $this->service->store($input);
                if ($request->isPost) {
                    try {
                        $customer->handleFormPhoto();
                    } catch (Exception $e) {
                        Yii::debug($e->getMessage());
                    }
                }
                $response['message'] = "Cliente {$customer->name} - {$customer->cpf} cadastrado com sucesso";
                $response['customer'] = $customer->asArray();
                return static::objectResponse($response);
            } catch(Exception $e) {
                $response['message'] = $e->getMessage();
                $response['customer'] = [];
                return static::objectResponse($response, 500);
            }
        } else {
            $response['message'] = "Cliente {$oldCustomer->name} - {$oldCustomer->cpf} já cadastrado";
            $response['customer'] = $oldCustomer;
        } 

        return static::objectResponse($response);
    }


    /**
     * 
     * @SWG\Path(
     *   path="/customers/{id}",
     *   @SWG\Post(
     *      tags={"clientes"},
     *      consumes={"multipart/form-data"},
     *      summary="Alterar Cliente.",
     *      operationId="updateCustomerForm",
     *      security={{"Bearer":{}}},
     *      @SWG\Parameter(
     *       name="id",
     *       in="path",
     *       description="identifier",
     *       required=true,
     *       type="integer", 
     *       @SWG\Items(type="integer"), 
     *       format="int32"
     *      ),
     *      @SWG\Parameter(
     *        description="Nome", 
     *        in="formData",
     *        name="name",
     *        required=true,
     *        type="string",
     *      ),
     *      @SWG\Parameter(
     *        description="CPF", 
     *        in="formData",
     *        name="cpf",
     *        required=true,
     *        type="string",
     *      ),
     *      @SWG\Parameter(
     *        description="Sexo", 
     *        in="formData",
     *        name="sex",
     *        required=true,
     *        type="string",
     *        maxLength=1,
     *      ),
     *      @SWG\Parameter(
     *        description="Foto", 
     *        in="formData",
     *        name="photo",
     *        required=false,
     *        type="file",
     *     ),
     *     @SWG\Parameter(
     *        description="CEP", 
     *        in="formData",
     *        name="cep",
     *        required=true,
     *        type="string",
     *        maxLength=12,
     *      ),
     *      @SWG\Parameter(
     *        description="uf", 
     *        in="formData",
     *        name="uf",
     *        required=true,
     *        type="string",
     *        maxLength=2,
     *      ),
     *      @SWG\Parameter(
     *        description="Cidade", 
     *        in="formData",
     *        name="cidade",
     *        required=true,
     *        type="string",
     *        maxLength=60,
     *      ),
     *      @SWG\Parameter(
     *        description="Rua", 
     *        in="formData",
     *        name="rua",
     *        required=true,
     *        type="string",
     *        maxLength=100,
     *      ),
     *      @SWG\Parameter(
     *        description="Número", 
     *        in="formData",
     *        name="numero",
     *        required=true,
     *        type="string",
     *        maxLength=20,
     *      ),
     *      @SWG\Parameter(
     *        description="Complemento", 
     *        in="formData",
     *        name="complemento",
     *        required=false,
     *        type="string",
     *        maxLength=200,
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "response",
     *         @SWG\Schema(ref="#/definitions/Customer")
     *     ),
     *   ),
     *    @SWG\Put(
     *     tags={"clientes"},
     *     summary="Alterar Cliente.",
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
     *        @SWG\Schema(
     *           @SWG\Property(property="name", type="string"),
     *           @SWG\Property(property="cpf", type="string"),
     *           @SWG\Property(property="sex", type="string"),
     *           @SWG\Property(property="cep", type="string"),
     *           @SWG\Property(property="uf", type="string"),
     *           @SWG\Property(property="cidade", type="string"),
     *           @SWG\Property(property="rua", type="string"),
     *           @SWG\Property(property="numero", type="string"),
     *           @SWG\Property(property="complemento", type="string"),
     *        )
     *      ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "response",
     *         @SWG\Schema(ref="#/definitions/Customer")
     *     ),
     *   ),
     * 
     * )
    */
    public function actionUpdate() {
        try {
            $request = Yii::$app->request;
            if ($request->isPost) {
                $input = Yii::$app->request->post();
            } else {
                $input = Yii::$app->request->getBodyParams();
            }
            $customer = $this->service->update(Yii::$app->request->get('id'), $input);
            if ($request->isPost) {
                try {
                    $customer->handleFormPhoto();
                } catch (Exception $e) {
                    Yii::debug($e->getMessage());
                }
            }
            $response['message'] = "Cliente {$customer->name} - {$customer->cpf} alterado com sucesso";
            $response['customer'] = $customer->asArray();
            return static::objectResponse($response);
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['customer'] = [];
            return static::objectResponse($response, 500);
        }
    }

}