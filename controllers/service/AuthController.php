<?php

namespace app\controllers\service;

use yii\filters\{AccessControl, VerbFilter};
use yii\web\{Controller, Response};
use app\models\form\LoginForm;
use Yii;

/**
 * Class AuthController
 *
 * @package app\controllers\service
 */
class AuthController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post']
                ],
            ],
        ];
    }

    /**
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['/']);
        }

        return $this->render('login', ['model' => $model]);
    }

    /**
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
