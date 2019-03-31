<?php

namespace app\commands;

use yii\base\{Exception, Model};
use yii\console\Controller;
use yii\helpers\Console;
use app\models\User;

/**
 * Class UserController
 *
 * @package app\commands
 */
class UserController extends Controller
{
    /**
     *
     */
    public function actionIndex()
    {
        echo 'yii users/create' . PHP_EOL;
    }

    /**
     * @throws Exception
     */
    public function actionCreate()
    {
        $model = new User();
        $this->readValue($model, 'username');
        $model->generateAuthKey();
        $model->setPassword(
            $this->prompt(
                'Password:',
                [
                    'required' => true,
                    'pattern' => '#^.{5,255}$#i',
                    'error' => 'More than 5 symbols',
                ]
            )
        );
        $model->role = User::ROLE_ADMIN;
        $this->readValue($model, 'full_name');
        $this->readValue($model, 'phone');
        $this->readValue($model, 'email');
        $model->status = User::STATUS_ACTIVE;
        $this->log($model->save());
    }

    /**
     * @param Model  $model
     * @param string $attribute
     */
    private function readValue($model, $attribute)
    {
        $model->$attribute = $this->prompt(mb_convert_case($attribute, MB_CASE_TITLE, 'utf-8') . ':',
            [
                'validator' => function ($input, &$error) use ($model, $attribute)
                {
                    $model->$attribute = $input;
                    if ($model->validate([$attribute])) {
                        return true;
                    } else {
                        $error = implode(',', $model->getErrors($attribute));

                        return false;
                    }
                },
            ]
        );
    }

    /**
     * @param bool $success
     */
    private function log($success)
    {
        if ($success) {
            $this->stdout('Success!', Console::FG_GREEN, Console::BOLD);
        } else {
            $this->stderr('Error!', Console::FG_RED, Console::BOLD);
        }
        echo PHP_EOL;
    }
}
