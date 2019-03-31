<?php

$config = [
    'id' => 'basic',
    'name' => 'Short Links',
    'language' => 'en',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
    ],
    'aliases' => [
        '@bower' => '@vendor/bower',
        '@npm' => '@vendor/npm',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => '0i7oMoAf-jMDnoTcaQfyZy7KWjUY2kMF',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:d.m.Y',
            'timeFormat' => 'php:H:i:s',
            'datetimeFormat' => 'php:Y.m.d H:i:s',
        ],
        'user' => [
            'identityClass' => 'app\models\UserAdapter',
            'enableAutoLogin' => false,
            'loginUrl' => ['login'],
        ],
        'errorHandler' => [
            'errorAction' => 'service/error/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'maxLogFiles' => 10,
                    'maxFileSize' => 2048,
                    'logVars' => [!YII_DEBUG ? : '$_GET, $_POST'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login' => 'service/auth/login',
                'logout' => 'service/auth/logout',

                '/' => 'url/index',
                'details' => 'url/details',
                'delete' => 'url/delete',
                '<code:\w+>' => 'url/forward',

            ],
        ],
        'db' => require(__DIR__ . '/local/local-db.php'),
    ],
    'params' => require(__DIR__ . '/local/local-params.php'),
];

if (YII_ENV_DEV) {
    $config['name'] .= ' (dev-env)';
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = ['class' => 'yii\debug\Module'];
}

if (YII_ENV_TEST) {
    $config['name'] .= ' (test-env)';
}

return $config;
