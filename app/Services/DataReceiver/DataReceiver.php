<?php
/**
 * Created by PhpStorm.
 * User: piter
 * Date: 16.11.2019
 * Time: 18:28
 */

namespace App\Services\DataReceiver;


use Illuminate\Http\Request;

interface DataReceiver
{
    public function getJsonData(Request $request):string;
}
