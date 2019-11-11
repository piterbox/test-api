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

class DailyBalanceTransformer
{
    use Transformer;

    public static function transform($entity)
    {

        return [
            'date' => Carbon::parse($entity->date)->toDateString(),
            'balance' => $entity->balance,
        ];
    }
}
