<?php

use yii\helpers\Url;
use app\models\User;

class AuthCest
{

    protected $user;

    public function _before(ApiTester $I)
    {
    }

    protected function makeUrl(string $url) { 
        return Url::toRoute($url, true);
    }

    // tests


    public function registerUserTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $input = [
            'name' => 'Administrador',
            'username' => 'admin', 
            'password' => 'admin',
        ];

        $user = User::findOne(['username' => $input['username']]);

        if (is_null($user)) {
            $I->sendPost($this->makeUrl('/api/v1/auth/register'), $input);
            $I->seeResponseCodeIsSuccessful();
            $I->seeResponseIsJson();
            $I->seeResponseContains('Bearer ');
        } else {
            $I->sendPost($this->makeUrl('/api/v1/auth/register'), $input);
            $I->canSeeResponseCodeIsServerError();
            $I->seeResponseIsJson();
            $I->seeResponseContainsJson(['message' => 'Username em uso']);
        }


    }


    public function getTokenTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $input = [
            'username' => 'admin', 
            'password' => 'admin',
        ];
        $I->sendPost($this->makeUrl('/api/v1/auth/token'), $input);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('Bearer ');
    }


    public function canViewProfileTest(ApiTester $I)
    {
        $this->user = User::findOne(['username' => 'admin']);
        $I->assertIsNotNull($this->user);
        if (is_null($this->user->access_token)) {
            $this->user->generateAccessToken();
        }
        $I->amBearerAuthenticated($this->user->access_token);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendGet($this->makeUrl('/api/v1/profile/user'));
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['username' => $this->user->username]);
    }

    public function invalidateTokenTest(ApiTester $I)
    {
        $this->user = User::findOne(['username' => 'admin']);
        $I->assertIsNotNull($this->user);
        if (is_null($this->user->access_token)) {
            $this->user->generateAccessToken();
        }
        $I->amBearerAuthenticated($this->user->access_token);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPost($this->makeUrl('/api/v1/profile/logout'));
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('Token Invalidated');
    }





    public function canNotViewProfileTest(ApiTester $I)
    {
        $this->user = User::findOne(['username' => 'admin']);
        $I->assertIsNotNull($this->user);
        $I->amBearerAuthenticated($this->user->access_token);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendGet($this->makeUrl('/api/v1/profile/user'));
        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
        $I->seeResponseContains('Unauthorized');   
    }


}
