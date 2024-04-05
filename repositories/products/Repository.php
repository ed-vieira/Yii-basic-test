<?php 

namespace app\repositories\products;

use app\interfaces\contracts\IRepository;
use app\models\Product;
use yii\data\ActiveDataProvider;
use app\utils\strings\Handler as StrHandler;
use Yii;
use Exception;
use yii\base\Model;

class Repository implements IRepository {

    protected $errors = [];

    public function store(array $input) {
        $tx = Product::getDb()->beginTransaction();
        try {
            $product = new Product();
            $product->name = $input['name'];
            $product->price = $input['price'];
            $product->customer_id = $input['customer_id'];
            $product->save();
            if ($product->hasErrors()) {
                throw new Exception();
            }
            $tx->commit();
            return $product;
        } catch (Exception $e) {
            $tx->rollBack();
            $this->errors = $product->getErrors();
            throw $e;
        }
    }

    public function update(int $id, array $input) {
        $tx = Product::getDb()->beginTransaction();
        try {
            $product = $this->findById($id);
            $product->name = $input['name'];
            $product->price = $input['price'];
            $product->save();
            if ($product->hasErrors()) {
                throw new Exception();
            }
            $tx->commit();
            return $product;
        } catch (Exception $e) {
            $tx->rollBack(); 
            $this->errors = $product->getErrors();
            throw $e;
        }
    }

    public function view(int $id) {
        return $this->findById($id);
    }

    public function findById(int $id):? Product {
        return Product::findOne(['id' => $id]);
    }

    public function find(array $query = []) {
        return Product::find()->where($query);
    }

    public function list(array $query) {
        return $this->find($query)->all();
    }

    public function paginate(array $input, int $perPage = 10, int $page = 1, array $orderBy= []) {
        $query = Product::find();
        $cols= ['{{products}}.*',];


        if (array_key_exists('cpf', $input) and !empty($input['cpf'])) {
            $query = $query->rightJoin('{{customers}}','{{customers}}.id = {{products}}.customer_id')
            ->asArray()
            ->where(['LIKE', '{{customers}}.cpf_number', StrHandler::digits($input['cpf'])]);
            $cols[] = '{{customers}}.name as customer_name';
            $cols[] = '{{customers}}.cpf as customer_cpf';
        }

        if (array_key_exists('name', $input) and !empty($input['name'])) {
            $query = $query->andWhere(['LIKE', '{{products}}.name', $input['name']]);
        }

        if (array_key_exists('customer_id', $input) and !empty($input['customer_id'])) {
            $query = $query->andwhere(['{{products}}.customer_id' => $input['customer_id']]);
        }

        return new ActiveDataProvider([
            'query' => $query->select($cols),
            'pagination' => [
                'pageSize' => $perPage,
                'page' => ($page - 1),
            ],
            'sort' => [
                'defaultOrder' => $orderBy,
            ],
        ]);
    }

    public function destroy(int $id): bool {
        $ok = false;
        $product = Product::findOne(['id' => $id]);
        if (! is_null($product)) {
            $product->delete();
            $ok = true;
        }
        return $ok;
    }


    public function getErrors(): array {
        return $this->errors;
    }


}