<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'debug'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ePD9dxiFYeJhBTyIfXB7ZfIKxxUUP2-l',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'loginUrl' => null, 
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
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
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];
}

return $config;
