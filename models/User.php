<?php

namespace app\models;

use aracoool\uuid\{Uuid, UuidBehavior, UuidValidator};
use yii\base\{Exception, NotSupportedException};
use app\helpers\{ErrorHelper, UserHelper};
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class User for table "{{%user}}".
 *
 * @property string  $id
 * @property string  $username
 * @property string  $auth_key
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property string  $role
 * @property string  $full_name
 * @property string  $phone
 * @property string  $email
 * @property string  $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * Статус
     * АКТИВНО
     */
    const STATUS_ACTIVE = 'Y';

    /**
     * Статус
     * ЗАБЛОКИРОВАНО
     */
    const STATUS_BLOCKED = 'N';

    /**
     * Роль
     * АДМИН АДМИНКИ
     */
    const ROLE_ADMIN = 'admin';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => UuidBehavior::class,
                'version' => Uuid::V4,
            ],
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['id', UuidValidator::class, 'message' => ErrorHelper::getAttributeLabel('uuid_wrong_format')],
            ['id', 'unique'],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 5, 'max' => 64],
            ['username', 'validateUsername'],

            ['role', 'trim'],
            ['role', 'required'],
            ['role', 'in', 'range' => array_keys(UserHelper::getRolesArray())],

            ['full_name', 'trim'],
            ['full_name', 'required'],
            ['full_name', 'string', 'min' => 5, 'max' => 255],

            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'string', 'min' => 5, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'min' => 5, 'max' => 255],
            ['email', 'validateEmail'],

            ['status', 'trim'],
            ['status', 'required'],
            ['status', 'in', 'range' => array_keys(UserHelper::getStatusesArray())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return UserHelper::attributeLabels();
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeLabel($attr)
    {
        return UserHelper::getAttributeLabel($attr);
    }

    /**
     * @param $attribute
     */
    public function validateUsername($attribute)
    {
        $entity = User::findOne(['username' => $this->username]);
        if (!$this->id && $entity !== null) {
            $this->addError($attribute, ErrorHelper::getAttributeLabel('taken_username'));
        }
        if ($entity !== null && $this->id && $entity->getId() !== $this->id) {
            $this->addError($attribute, ErrorHelper::getAttributeLabel('taken_username'));
        }
    }

    /**
     * @param $attribute
     */
    public function validateEmail($attribute)
    {
        $entity = User::findOne(['email' => $this->email]);
        if (!$this->id && $entity !== null) {
            $this->addError($attribute, ErrorHelper::getAttributeLabel('taken_email'));
        }
        if ($entity !== null && $this->id && $entity->getId() !== $this->id) {
            $this->addError($attribute, ErrorHelper::getAttributeLabel('taken_email'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->full_name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * {@inheritdoc}
     *
     * @throws NotSupportedException
     */
    public static function findIdentity($id)
    {
        throw new NotSupportedException();
    }

    /**
     * {@inheritdoc}
     *
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException();
    }

    /**
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param $password
     *
     * @throws Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @throws Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @throws Exception
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @param $id
     *
     * @return User|null
     */
    public static function findById($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @param $id
     *
     * @return User|null
     */
    public static function findActiveById($id)
    {
        return static::findOne(['id' => $id, 'status' => User::STATUS_ACTIVE]);
    }

    /**
     * @param $id
     *
     * @return User|null
     */
    public static function findBlockedById($id)
    {
        return static::findOne(['id' => $id, 'status' => User::STATUS_BLOCKED]);
    }

    /**
     * @param string $username
     *
     * @return User|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param string $phone
     *
     * @return User|null
     */
    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne(['password_reset_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }
}
