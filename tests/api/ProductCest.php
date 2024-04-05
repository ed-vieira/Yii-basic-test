<?php

use app\models\Customer;
use app\models\Product;
use app\models\User;
use yii\helpers\Url;

class ProductCest
{

    const url = '/api/v1/products';

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
    public function storeProductTest(ApiTester $I) {
        $faker = Faker\Factory::create('pt_BR');
        $I->amBearerAuthenticated($this->user->access_token);
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->haveHttpHeader('Accept', 'application/json');
        $customer = Customer::find()->orderBy(['id' => SORT_DESC])->one();
        if (! is_null($customer)) {
            $productName = '';
            foreach ($faker->words(random_int(1, 5)) as $words) {
               $productName.= ' '.$words;
            }
            $input = [
                'name' => ltrim($productName), 
                'price' =>  random_int(1000, 1000000),
                'customer_id' => $customer->id,
            ];
            $I->sendPost($this->makeUrl(), $input);
            $I->seeResponseContainsJson(['customer_id' => $input['customer_id']]);
            $I->seeResponseContainsJson(['name' => $input['name']]);
            $I->seeResponseContainsJson(['price' => $input['price']]);
            $I->seeResponseCodeIsSuccessful();
            $I->seeResponseIsJson();
        }
    }


    public function updateProductTest(ApiTester $I) {
        $product = Product::find()->orderBy(['id'=> SORT_ASC])->one();
        if (! is_null($product)) {
            $faker = Faker\Factory::create('pt_BR');
            $I->amBearerAuthenticated($this->user->access_token);
            $I->haveHttpHeader('Content-Type', 'application/json');
            $I->haveHttpHeader('Accept', 'application/json');
            $productName = '';
            foreach ($faker->words(random_int(1, 5)) as $words) {
               $productName.= ' '.$words;
            }
            $input = [
                'name' => $productName, 
                'price' =>  random_int(1000, 1000000),
            ];
            $I->sendPut($this->makeUrl($product->id), $input);
            $I->seeResponseCodeIsSuccessful();
            $I->seeResponseIsJson();
            $I->seeResponseContainsJson(['name' => $input['name']]);
            $I->seeResponseContainsJson(['price' => $input['price']]);
        }
    }


    public function listProductsTest(ApiTester $I) {
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


    public function viewProductTest(ApiTester $I) {
        $product = Product::find()->where(['in', 'id', [
               random_int(1, 10),
               random_int(1, 50),
               random_int(1, 100) 
            ]])->one();
        if (! is_null($product)) {
            $I->amBearerAuthenticated($this->user->access_token);
            $I->haveHttpHeader('Accept', 'application/json');
            $I->sendGet($this->makeUrl($product->id));
            $I->seeResponseCodeIsSuccessful();
            $I->seeResponseIsJson();
        }
    }


    public function destroyProductTest(ApiTester $I) {
        $product = Product::find()->where(['in', 'id', [
               random_int(1, 10),
               random_int(1, 50),
               random_int(1, 100) 
            ]])->one();
        if (! is_null($product)) {
            $I->amBearerAuthenticated($this->user->access_token);
            $I->haveHttpHeader('Accept', 'application/json');
            $I->sendDelete($this->makeUrl($product->id));
            $I->seeResponseCodeIsSuccessful();
            $I->seeResponseIsJson();
            $I->seeResponseContainsJson(['message' => 'Ok']);
        }
    }


}
