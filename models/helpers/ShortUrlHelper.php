<?php

namespace app\models\helpers;

/**
 * Class ShortUrlHelper
 *
 * @package app\models\helpers
 */
class ShortUrlHelper
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
            'long_url' => 'URL (long)',
            'short_code' => 'URL (short)',
            'counter' => 'Usage count',
            'created_at' => 'Created',
            'updated_at' => 'Updated',

            'title' => 'Short Url',
            'placeholder' => 'http://yousite.com/',
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
