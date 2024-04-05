<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'en-US',
    'components' => [
        'db' => $db,
        'mailer' => [
            'useFileTransport' => true,
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [

                'POST api/v1/auth/token'  => 'auth/token',
                'POST api/v1/auth/register'  => 'auth/register',
                'GET  api/v1/profile/user'   => 'profile/user',
                'POST api/v1/profile/logout'   => 'profile/logout',

                'GET  api/v1/customers'            => 'customers/index',
                'POST api/v1/customers'            => 'customers/store',
                'PUT  api/v1/customers'            => 'customers/store',
                'GET  api/v1/customers/<id:\d+>'   => 'customers/show',
                'POST api/v1/customers'            => 'customers/store',
                'POST api/v1/customers/<id:\d+>'   => 'customers/update',
                'PUT  api/v1/customers/<id:\d+>'   => 'customers/update',
                'DELETE api/v1/customers/<id:\d+>' => 'customers/delete',

                'GET  api/v1/products'            => 'products/index',
                'POST api/v1/products'            => 'products/store',
                'PUT  api/v1/products'            => 'products/store',
                'GET  api/v1/products/<id:\d+>'   => 'products/show',
                'POST api/v1/products'            => 'products/store',
                'PUT  api/v1/products/<id:\d+>'   => 'products/update',
                'DELETE api/v1/products/<id:\d+>' => 'products/delete',

                'GET  api/v1/customer/<customer:\d+>/products'            => 'customer-products/index',
                'POST api/v1/customer/<customer:\d+>/products'            => 'customer-products/store',
                'PUT  api/v1/customer/<customer:\d+>/products'            => 'customer-products/store',
                'GET  api/v1/customer/<customer:\d+>/products/<id:\d+>'   => 'customer-products/show',
                'POST api/v1/customer/<customer:\d+>/products/<id:\d+>'   => 'customer-products/update',
                'PUT  api/v1/customer/<customer:\d+>/products/<id:\d+>'   => 'customer-products/update',
                'DELETE api/v1/customer/<customer:\d+>/products/<id:\d+>' => 'customer-products/delete',
                
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'loginUrl' => null
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
    ],
    'params' => $params,
];
