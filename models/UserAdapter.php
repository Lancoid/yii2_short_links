<?php

namespace app\models;

use yii\web\{IdentityInterface, Session};
use yii\base\InvalidConfigException;
use Yii;

/**
 * Class UserAdapter
 *
 * @package app\models
 */
final class UserAdapter implements IdentityInterface
{
    /** @var User $model */
    private $model;

    /**
     * IdentityAdapter constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        $this->model = User::findActiveById($id === null ? '' : $id);
    }

    /**
     * @return User
     */
    public function getModel(): ?User
    {
        return $this->model;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->model->getId();
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->model->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->model->getRole();
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->model->getFullName();
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->model->getPhone();
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->model->getEmail();
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->model->getStatus();
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->model->getCreatedAt();
    }

    /**
     * @return int
     */
    public function getUpdatedAt(): int
    {
        return $this->model->getUpdatedAt();
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidConfigException
     */
    public static function findIdentity($id): ?self
    {
        /* @var $self self */
        $self = Yii::createObject(self::class, [$id]);

        if ($self->getModel() !== null) {
            return $self;
        } else {
            (new Session())->destroy();

            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return User::find()
            ->joinWith('tokens t')
            ->where(['t.token' => $token])
            ->andWhere(['>', 't.expired_at', time()])
            ->one();
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->getRole() === User::ROLE_ADMIN ? true : false;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->getStatus() === User::STATUS_ACTIVE ? true : false;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->getStatus() === User::STATUS_BLOCKED ? true : false;
    }
}
