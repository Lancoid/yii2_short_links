<?php

namespace app\helpers;

/**
 * Class ShortUrlLogHelper
 *
 * @package app\helpers
 */
class ShortUrlLogHelper
{
    /**
     * Returns the attribute labels.
     *
     * @return array attribute labels (name => label)
     */
    public static function attributeLabels()
    {
        return [
            'id' => 'ID',
            'short_url_id' => 'ID short url',
            'user_platform' => 'Platform',
            'user_agent' => 'Browser',
            'user_refer' => 'Refer',
            'user_ip' => 'Ip',
            'user_country' => 'Country',
            'user_city' => 'City',
            'created_at' => 'Created',

            'title' => 'Details for short code - '
        ];
    }

    /**
     * Returns the text label for the specified attribute.
     *
     * @param string $attribute the attribute name
     *
     * @return string the attribute label
     */
    public static function getAttributeLabel($attribute)
    {
        return self::attributeLabels()[$attribute];
    }
}
