<?php
/**
 * Created by PhpStorm.
 * User: piter
 * Date: 10.11.2019
 * Time: 19:09
 */

namespace App\Transformers;


use App\Traits\Transformer;
use Carbon\Carbon;

class TransactionTransformer
{
    use Transformer;

    public static function transform($entity)
    {

        return [
            'id' => $entity->id,
            'type' => $entity->type,
            'date' => Carbon::parse($entity->date)->toDateString(),
            'amount' => $entity->amount,
            'text' => $entity->text,
            'balance' => $entity->balance,
            'tags' => $entity->tags,

        ];
    }
}
