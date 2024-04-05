<?php

use app\models\Customer;
use app\models\User;
use Helper\Api;
use yii\helpers\Url;
use app\utils\strings\Handler as StrHandler;

class CustomerCest
{

    const url = '/api/v1/customers';

    protected $user;

    public function _before(ApiTester $I) {
        $this->user = User::findOne(['username' => 'admin']);
        $I->assertIsNotNull($this->user);
        if (is_null($this->user->access_token)) {
            $this->user->generateAccessToken();
        }
    }

    protected function makeUrl(string $id = '') { 
        if (! empty($id)) {
            return Url::toRoute(static::url.'/'.$id, true);
        }
        return Url::toRoute(static::url, true);
    }

    // tests
    public function storeCustomerTest(ApiTester $I) {
        $faker = Faker\Factory::create('pt_BR');
        $I->amBearerAuthenticated($this->user->access_token);
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->haveHttpHeader('Accept', 'application/json');
        $cpf = $faker->cpf(true);
        $gender = $faker->randomElements(['male', 'female'])[0];
        $genders = ['male' => 'm', 'female' => 'f'];
        $input = [
            'name' => $faker->name($gender), 
            'cpf' =>  $cpf,
            'sex' => $genders[$gender],
            'uf' => $faker->stateAbbr(),
            'cep' => $faker->postcode(),
            'cidade' => $faker->city(),
            'rua' => $faker->streetName(),
            'numero' => $faker->randomDigitNotNull(),
            'complemento' => 'Teste'
        ];
        if (StrHandler::validateCPF($cpf)) {
            $I->sendPost($this->makeUrl(), $input);
            $I->seeResponseCodeIsSuccessful();
            $I->seeResponseIsJson();
        } else {
            $I->sendPost($this->makeUrl(), $input);
            $I->canSeeResponseCodeIsServerError();
            $I->seeResponseIsJson();
            $I->seeResponseContains('CPF inválido');
        }
    }


    public function updateCustomerTest(ApiTester $I)
    {
        $customer = Customer::find()->orderBy(['id'=> SORT_ASC])->one();
        if (! is_null($customer)) {
            $faker = Faker\Factory::create('pt_BR');
            $I->amBearerAuthenticated($this->user->access_token);
            $I->haveHttpHeader('Content-Type', 'application/json');
            $I->haveHttpHeader('Accept', 'application/json');
            $genders = ['m' => 'male', 'f' => 'female'];
            $input = [
                'name' => $faker->name($genders[$customer->gender]), 
                'cpf' =>  $customer->cpf,
                'sex' => $customer->gender,
                'uf' => $customer->address->state,
                'cep' => $customer->address->post_code,
                'cidade' => $customer->address->city,
                'rua' => $customer->address->street,
                'numero' => $customer->address->number,
            ];
            if (StrHandler::validateCPF($input['cpf'])) {
                $I->sendPost($this->makeUrl($customer->id), $input);
                $I->seeResponseCodeIsSuccessful();
                $I->seeResponseIsJson();
                $I->seeResponseContains($input['name']);
            } else {
                $I->sendPost($this->makeUrl($customer->id), $input);
                $I->seeResponseCodeIsServerError();
                $I->seeResponseIsJson();
                $I->seeResponseContains('CPF inválido');
            }

        }
    }


    public function listCustomerTest(ApiTester $I) {
        $perPage = random_int(1, 10);
        $page = random_int(1, 5);
        $I->amBearerAuthenticated($this->user->access_token);
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendGet($this->makeUrl(), [
          'per_page' => $perPage, 
          'page' => $page,
        ]);
        $I->seeHttpHeader('x-pagination-per-page', $perPage);
        $I->seeHttpHeader('x-pagination-current-page', $page);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

    }


    public function viewCustomerTest(ApiTester $I) {
        $customer = Customer::find()->where(['in', 'id', [
               random_int(1, 10),
               random_int(1, 50),
               random_int(1, 100) 
            ]])->one();
        if (! is_null($customer)) {
            $I->amBearerAuthenticated($this->user->access_token);
            $I->haveHttpHeader('Accept', 'application/json');
            $I->sendGet($this->makeUrl($customer->id));
            $I->seeResponseCodeIsSuccessful();
            $I->seeResponseIsJson();
        }
    }


    public function destroyCustomerTest(ApiTester $I) {
        $customer = Customer::find()->where(['in', 'id', [
               random_int(1, 10),
               random_int(1, 50),
               random_int(1, 100) 
            ]])->one();
        if (! is_null($customer)) {
            $I->amBearerAuthenticated($this->user->access_token);
            $I->haveHttpHeader('Accept', 'application/json');
            $I->sendDelete($this->makeUrl($customer->id));
            $I->seeResponseCodeIsSuccessful();
            $I->seeResponseIsJson();
        }
    }


}
