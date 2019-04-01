<?php

namespace app\models;

use aracoool\uuid\{Uuid, UuidBehavior, UuidValidator};
use yii\web\{HttpException, NotFoundHttpException};
use app\helpers\{ErrorHelper, ShortUrlHelper};
use yii\db\{ActiveRecord, ActiveQuery};
use yii\behaviors\TimestampBehavior;
use yii\helpers\BaseArrayHelper;
use linslin\yii2\curl;
use Exception;
use Yii;

/**
 * This is the model class ShortUrl for table "{{%short_url}}".
 *
 * @property string        $id
 * @property string        $long_url
 * @property string        $short_code
 * @property integer       $counter
 * @property integer       $created_at
 * @property integer       $updated_at
 *
 * @property ShortUrlLog[] $logs
 */
class ShortUrl extends ActiveRecord
{
    /** @var (string) Allowed characters for short urls */
    const ALLOWED_CHARS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%short_url}}';
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

            ['long_url', 'required'],
            ['long_url', 'url'],
            ['long_url', 'unique'],
            ['long_url', 'checkUrl'],

            ['short_code', 'required'],
            ['short_code', 'string', 'max' => 6],
            ['short_code', 'unique'],

            ['counter', 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ShortUrlHelper::attributeLabels();
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeLabel($attr)
    {
        return ShortUrlHelper::getAttributeLabel($attr);
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
    public function getLongUrl(): string
    {
        return $this->long_url;
    }

    /**
     * @return string
     */
    public function getShortCode(): string
    {
        return $this->short_code;
    }

    /**
     * @return int
     */
    public function getCounter(): int
    {
        return $this->counter;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->created_at;
    }

    /**
     * @return int
     */
    public function getUpdatedAt(): int
    {
        return $this->updated_at;
    }

    /**
     * @return ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(ShortUrlLog::class, ['short_url_id' => 'id']);
    }

    /**
     * @return string
     */
    public function genShortCode()
    {
        do {
            $shortCode = substr(str_shuffle(self::ALLOWED_CHARS), 0, 6);
        } while (self::findByShortCode($shortCode));

        $this->short_code = $shortCode;
    }

    /**
     * @param string $shortCode
     *
     * @return ShortUrl|array|ActiveRecord|null
     */
    public static function findByShortCode(string $shortCode)
    {
        return ShortUrl::find()->where(['short_code' => $shortCode])->one();
    }

    /**
     * @param string $shortCode
     *
     * @return ShortUrl|array|ActiveRecord|null
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public static function validateShortCode(string $shortCode)
    {
        if (!preg_match('|^[0-9a-zA-Z]{6,6}$|', $shortCode)) {
            throw new HttpException(400, ErrorHelper::getAttributeLabel('short_code_not_valid'));
        }

        $url = self::findByShortCode($shortCode);

        if (!$url) {
            throw new NotFoundHttpException(ErrorHelper::getAttributeLabel('short_code_not_found') . $shortCode);
        }

        return $url;
    }

    /**
     * @param $attribute
     *
     * @throws Exception
     */
    public function checkUrl($attribute)
    {
        $curl = new curl\Curl();

        $curl->setOption(CURLOPT_SSL_VERIFYHOST, false);
        $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOption(CURLOPT_FOLLOWLOCATION, true);
        $curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $curl->setOption(CURLOPT_AUTOREFERER, true);
        $curl->setOption(CURLOPT_CONNECTTIMEOUT, 60);
        $curl->setOption(CURLOPT_TIMEOUT, 30);
        $curl->setOption(CURLOPT_MAXREDIRS, 10);
        $curl->setOption(CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36');

        $curl->get($this->long_url);

        if ($curl->responseCode !== 200) {
            $this->addError($attribute, ErrorHelper::getAttributeLabel('wrong_long_url') . $curl->errorText . ').');
        }
    }

    /**
     * Build array to view in Google Charts
     *
     * @param ShortUrl $shortUrl
     *
     * @return array
     */
    public static function getLogDetails(ShortUrl $shortUrl)
    {
        $usersInfo = BaseArrayHelper::toArray($shortUrl->logs);

        return [
            'user_platform' => static::getUsersInfo($usersInfo, 'user_platform'),
            'user_agent' => static::getUsersInfo($usersInfo, 'user_agent'),
            'user_refer' => static::getUsersInfo($usersInfo, 'user_refer'),
            'user_ip' => static::getUsersInfo($usersInfo, 'user_ip'),
            'user_country' => static::getUsersInfo($usersInfo, 'user_country'),
            'user_city' => static::getUsersInfo($usersInfo, 'user_city'),
        ];
    }

    /**
     * @param array  $usersInfo
     * @param string $name
     *
     * @return array
     */
    private static function getUsersInfo(array $usersInfo, string $name)
    {
        $array = [];
        $usersInfo = array_filter(BaseArrayHelper::getColumn($usersInfo, $name, false));
        $usersInfo = array_count_values($usersInfo);

        foreach ($usersInfo as $key => $value) {
            $array[] = [$key, $value];
        }

        return $array;
    }
}
