<?php
/**
 * Created by PhpStorm.
 * User: piter
 * Date: 10.11.2019
 * Time: 19:01
 */

namespace App\Transformers;


use App\Traits\Transformer;

class CustomerTransformer
{
    use Transformer;

    public static function transform($entity)
    {
        return [
            'id' => $entity->id,
            'name' => $entity->first_name . ' ' .$entity->last_name,
            'encryption_key' => $entity->encryption_key
        ];
    }
}
