<?php

namespace app\controllers\service;

use yii\web\Controller;

/**
 * Class ErrorController
 *
 * @package app\controllers\service
 */
class ErrorController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $layout = '@app/views/layouts/main';

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}
