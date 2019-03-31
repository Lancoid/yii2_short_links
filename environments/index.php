<?php

return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'runtime/cache',
            'runtime/logs',
            'web/assets',
        ],
        'setExecutable' => ['yii'],
        'setCookieValidationKey' => ['config/web.php'],
    ],
    'Test' => [
        'path' => 'test',
        'setWritable' => [
            'runtime/cache',
            'runtime/logs',
            'web/assets',
        ],
        'setExecutable' => ['yii'],
        'setCookieValidationKey' => ['config/web.php'],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'runtime/cache',
            'runtime/logs',
            'web/assets',
        ],
        'setExecutable' => ['yii'],
        'setCookieValidationKey' => ['config/web.php'],
    ],
];
