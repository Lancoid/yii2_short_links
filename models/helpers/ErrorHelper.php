<?php

namespace app\models\helpers;

/**
 * Class ErrorHelper
 *
 * @package app\models\helpers
 */
class ErrorHelper
{
    /**
     * Returns the attribute labels.
     *
     * @return array attribute labels (name => label)
     */
    public static function attributeLabels()
    {
        return [
            'incorrect_username_password' => 'Incorrect username or password.',
            'taken_username' => 'This username already taken.',
            'taken_email' => 'This e-mail already taken.',

            'uuid_wrong_format' => 'UUID wrong format',

            'wrong_long_url' => 'Something is wrong with your URL (error : ',
            'error_save_short_url' => 'Error on save short url.',
            'short_code_not_valid' => 'Please enter valid short code.',
            'short_code_not_found' => 'This short code not found: ',
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
