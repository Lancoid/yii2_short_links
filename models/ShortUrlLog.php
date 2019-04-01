<?php

namespace app\models;

use aracoool\uuid\{Uuid, UuidBehavior, UuidValidator};
use app\helpers\{ErrorHelper, ShortUrlLogHelper};
use yii\db\{ActiveRecord, ActiveQuery};
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%short_url_log}}".
 *
 * @property string      $id
 * @property string      $short_url_id
 * @property string|null $user_platform
 * @property string|null $user_agent
 * @property string|null $user_refer
 * @property string      $user_ip
 * @property string|null $user_country
 * @property string|null $user_city
 * @property integer     $created_at
 *
 * @property ShortUrl    $shortUrl
 */
class ShortUrlLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%short_url_log}}';
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
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
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

            ['short_url_id', 'required'],
            ['short_url_id', UuidValidator::class, 'message' => ErrorHelper::getAttributeLabel('uuid_wrong_format')],
            [
                'short_url_id',
                'exist',
                'targetClass' => ShortUrl::class,
                'targetAttribute' => ['short_url_id' => 'id'],
            ],

            ['user_ip', 'required'],
            [['user_platform', 'user_agent', 'user_refer', 'user_ip', 'user_country', 'user_city'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ShortUrlLogHelper::attributeLabels();
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeLabel($attr)
    {
        return ShortUrlLogHelper::getAttributeLabel($attr);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getShortUrlId(): string
    {
        return $this->short_url_id;
    }

    /**
     * @return string
     */
    public function getUserPlatform(): string
    {
        return $this->user_platform;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->user_agent;
    }

    /**
     * @return string
     */
    public function getUserRefer(): string
    {
        return $this->user_refer;
    }

    /**
     * @return string
     */
    public function getUserIp(): string
    {
        return $this->user_ip;
    }

    /**
     * @return string
     */
    public function getUserCountry(): string
    {
        return $this->user_country;
    }

    /**
     * @return string
     */
    public function getUserCity(): string
    {
        return $this->user_city;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->created_at;
    }

    /**
     * @return ActiveQuery
     */
    public function getShortUrl()
    {
        return $this->hasOne(ShortUrl::class, ['id' => 'short_url_id']);
    }
}
