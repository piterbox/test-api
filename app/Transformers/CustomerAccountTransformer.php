<?php
/**
 * Created by PhpStorm.
 * User: piter
 * Date: 10.11.2019
 * Time: 19:09
 */

namespace App\Transformers;


use App\Traits\Transformer;

class CustomerAccountTransformer
{
    use Transformer;

    public static function transform($entity)
    {
        return [
            'id' => $entity->id,
            'number' => $entity->number,
            'holder' => $entity->holder,
            'bsb' => $entity->bsb,
            'balance' => $entity->balance,
            'available' => $entity->available,
            'type' => $entity->type,
        ];
    }
}
