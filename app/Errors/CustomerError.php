<?php
/**
 * Created by PhpStorm.
 * User: piter
 * Date: 10.11.2019
 * Time: 20:37
 */

namespace App\Errors;


class CustomerError implements ErrorInterface
{
    private static $errors = [
        'error_encryption_key' => [
            'error_code' => 40101,
            'error_message' => 'There was an encryption error. The key maybe invalid.'
        ]
    ];

    public static function getError($key)
    {
        return self::$errors[$key] ?? null;
    }

    public static function getErrorCode($key)
    {
        return self::$errors[$key]['code'] ?? null;
    }

    public static function getErrorMessage($key)
    {
        return self::$errors[$key]['message'] ?? null;
    }

}
