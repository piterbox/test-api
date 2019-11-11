<?php
/**
 * Created by PhpStorm.
 * User: piter
 * Date: 10.11.2019
 * Time: 20:37
 */

namespace App\Errors;


interface ErrorInterface
{
    public static function getError($key);
}
