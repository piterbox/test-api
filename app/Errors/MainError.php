<?php
/**
 * Created by PhpStorm.
 * User: piter
 * Date: 10.11.2019
 * Time: 20:37
 */

namespace App\Errors;


class MainError implements ErrorInterface
{
    private static $errors = [
        'not_found' => [
            'error_code' => 10001,
            'error_message' => 'Not found.'
        ],
        'server_error' => [
            'error_code' => 10002,
            'error_message' => 'Server Error'
        ],
        'bad_request' => [
            'error_code' => 10003,
            'error_message' => 'Bad Request'
        ],
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
