<?php

namespace app\models\form;

use app\models\helpers\ErrorHelper;
use app\models\User;
use yii\base\Model;
use Yii;

/**
 * Class LoginForm
 *
 * @package app\models\form
 */
class LoginForm extends Model
{
    /** @var string $username */
    public $username;

    /** @var string $password */
    public $password;

    /** @var bool $rememberMe */
    public $rememberMe = true;

    /** @var User $userEntity */
    private $userEntity;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'string', 'max' => 255],

            ['password', 'required'],
            ['password', 'string', 'max' => 255],
            ['password', 'validatePassword'],

            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, ErrorHelper::getAttributeLabel('incorrect_username_password'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by `username`
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->userEntity === null) {
            $this->userEntity = User::findByUsername($this->username);
        }

        return $this->userEntity;
    }
}
