<?php

namespace app\helpers;

/**
 * Class MainHelper
 *
 * @package app\helpers
 */
class MainHelper
{
    /**
     * Returns the attribute labels.
     *
     * @return array attribute labels (name => label)
     */
    public static function attributeLabels()
    {
        return [
            'login' => 'Login',
            'logout' => 'Logout',
            'save' => 'Save',
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
