<?php
/**
 * Created by PhpStorm.
 * User: piter
 * Date: 10.11.2019
 * Time: 19:03
 */

namespace App\Traits;


trait Transformer
{
    public static function transformCollection($collection)
    {
        $result = [];
        foreach ($collection as $item){
            $result[] = self::transform($item);
        }
        return $result;
    }

}
