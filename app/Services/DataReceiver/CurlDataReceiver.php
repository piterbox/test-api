<?php
/**
 * Created by PhpStorm.
 * User: piter
 * Date: 16.11.2019
 * Time: 18:29
 */

namespace App\Services\DataReceiver;


use Illuminate\Http\Request;

class CurlDataReceiver implements DataReceiver
{
    protected $curl;

    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function getJsonData(Request $request): string
    {
        if(!$request) return false;

        curl_setopt($this->curl, CURLOPT_URL, $request->fullUrl());
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($request->toArray()));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($request->toArray()))
            )
        );

        try{
            $result = curl_exec($this->curl);
            curl_close($this->curl);
            return $result;

        } catch(\Exception $e) {
            //to do with exception
        }
    }
}
