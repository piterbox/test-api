<?php
/**
 * Created by PhpStorm.
 * User: piter
 * Date: 10.11.2019
 * Time: 21:13
 */

namespace App\Traits;


trait Decryptor
{
    public function encryptKey(string $key): string
    {
        return $key;
    }

    public function decryptKey(string $key): string
    {
        return $key;
    }

    public function generateKey()
    {
        return uniqid();
    }
}
