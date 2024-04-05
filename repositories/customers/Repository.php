<?php 

namespace app\repositories\customers;

use app\interfaces\contracts\IRepository;
use app\models\Customer;
use app\models\CustomerAddress;
use yii\data\ActiveDataProvider;
use app\utils\strings\Handler as StrHandler;
use Yii;
use Exception;
use Throwable;

class Repository implements IRepository {

    protected $errors = [];

    public function store(array $input) {
        $tx = Customer::getDb()->beginTransaction();
        try {
            $customer = new Customer();
            $customer->name = $input['name'];
            $customer->cpf = $input['cpf'];
            $customer->cpf_number = StrHandler::digits($input['cpf']);
            $customer->gender = $input['sex'];
            $customer->save();

            if ($customer->hasErrors()) {
                throw new Exception();
            }

            $address = new CustomerAddress(); 
            $address->customer_id = $customer->id;
            $address->post_code = $input['cep'];
            $address->state = $input['uf'];
            $address->city = $input['cidade'];
            $address->street = $input['rua'];
            $address->number = $input['numero'];
            if (array_key_exists('complemento', $input)) {
                $address->complement = $input['complemento'];
            }
            $address->save();

            if ($address->hasErrors()) {
                throw new Exception();
            }

            $tx->commit();
            return $customer;
        } catch (Exception $e) {
            $tx->rollBack();
            if (isset($customer) && isset($address)) {
                $this->errors = array_merge($customer->getErrors(), $address->getErrors());
            } else {
                $this->errors = $customer->getErrors();
            }
            $message = '';
            foreach ($this->errors as $key => $errors) {
                foreach($errors as $error) {
                    $message.="$error ";
                }
            }
            throw new Exception($message);
        }
    }

    public function update(int $id, array $input) {
        $tx = Customer::getDb()->beginTransaction();
        try {
            $customer = $this->findById($id);
            $customer->name = $input['name'];
            $customer->cpf = $input['cpf'];
            $customer->cpf_number = StrHandler::digits($input['cpf']);
            $customer->gender = $input['sex'];
            $customer->save();

            if ($customer->hasErrors()) {
                throw new Exception();
            }

            $address = $customer->address;
            $address->post_code = $input['cep'];
            $address->state = $input['uf'];
            $address->city = $input['cidade'];
            $address->street = $input['rua'];
            $address->number = $input['numero'];
            if (array_key_exists('complemento', $input)) {
                $address->complement = $input['complemento'];
            }
            $address->save();

            if ($address->hasErrors()) {
                throw new Exception();
            }
    
            $tx->commit();
            return $customer;
        } catch (Exception $e) {
            $tx->rollBack();
            if (isset($customer) && isset($address)) {
                $this->errors = array_merge($customer->getErrors(), $address->getErrors());
            } else {
                $this->errors = $customer->getErrors();
            }
            $message = '';
            foreach ($this->errors as $key => $errors) {
                foreach($errors as $error) {
                    $message.="$error ";
                }
            }
            throw new Exception($message);
        }
    }

    public function view(int $id) {
        return $this->findById($id);
    }

    public function findById(int $id) {
        return Customer::findOne(['id' => $id]);
    }

    public function find(array $query = []) {
        return Customer::find()->where($query);
    }

    public function list(array $query) {
        return $this->find($query)->all();
    }

    public function paginate(array $input, int $perPage = 10, int $page = 1, array $orderBy= []) {
        $query = Customer::find();
        
        if (array_key_exists('name', $input) and !empty($input['name'])) {
            $query = $query->andWhere(['LIKE', 'name', $input['name']]);
        }

        if (array_key_exists('cpf', $input) and !empty($input['cpf'])) {
            $query = $query->andWhere(['LIKE', 'cpf_number', StrHandler::digits($input['cpf'])]);
        }

        return new ActiveDataProvider([
            'query' => $query->select(['id', 'cpf', 'name', 'gender', 'photo', 'created_at', 'updated_at']),
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
        $customer = Customer::findOne(['id' => $id]);
        if (! is_null($customer)) {
            $customer->delete();
            $ok = true;
        }
        return $ok;
    }


    public function getErrors(): array {
        return $this->errors;
    }


}