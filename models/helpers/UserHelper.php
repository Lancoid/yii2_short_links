<?php

namespace app\models\helpers;

use app\models\User;

/**
 * Class UserHelper
 *
 * @package app\models\helpers
 */
class UserHelper
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
            'username' => 'Username',
            'auth_key' => 'Auth_key',
            'password_hash' => 'Password hash',
            'password' => 'Password',
            'password_repeat' => 'Password repeat',
            'password_reset_token' => 'Password reset token',
            'role' => 'Role',
            'full_name' => 'Full name',
            'phone' => 'Phone',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
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

    /**
     * @return array statuses (name => label)
     */
    public static function getStatusesArray()
    {
        return [
            User::STATUS_BLOCKED => 'Blocked',
            User::STATUS_ACTIVE => 'Active',
        ];
    }

    /**
     * @return array roles (name => label)
     */
    public static function getRolesArray()
    {
        return [
            User::ROLE_ADMIN => 'Admin',
        ];
    }
}
