<?php
/**
 * Created by PhpStorm.
 * User: piter
 * Date: 16.11.2019
 * Time: 20:13
 */

namespace App\Console\Commands;


use App\Customer;
use App\CustomerAccount;
use App\DailyBalance;
use App\Services\DataReceiver\CurlDataReceiver;
use App\Transaction;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class GetCustomerBankAccounts extends Command
{
    protected $signature = 'customer-bank-accounts:get';
    protected $description = 'Getting customer\'s information about bank accounts';
    protected $receiver;

    public function __construct(CurlDataReceiver $receiver)
    {
        parent::__construct();
        $this->receiver = $receiver;
    }

    public function handle()
    {
        $customers = Customer::all();
        if($customers->isEmpty()) return false;

        foreach ($customers as $customer){
            if($customer->accounts->isEmpty()){
                continue;
            }
            $request_data = [
                'customerId' => $customer->customer_id,
                'encryptionKey' => $customer->encryption_key,
            ];

            $request = Request::create(
                env('CUSTOMER_DATA_URL', 'http://test-api.com/customer/accounts'),
                'POST',
                $request_data);

            //send request
            $result = $this->receiver->getJsonData($request);
            if(!$result) {
                //write to logs in db or something else
                continue;
            }

            $data = json_decode($result, TRUE);

            if(isset($data['error_code'])){
                //write to log
                continue;
            }
            if(!isset($data['customer']['encryptionKey']) || $data['customer']['encryptionKey']) continue;

            $customer->encryption_key = $data['customer']['encryptionKey'];
            $customer->save();

            if(!isset($data['accounts']) || count($data['accounts']) == 0) continue;
            //update accounts
            $accounts = $data['accounts'];
            foreach ($accounts as $account){
                $old_account = CustomerAccount::find([
                    'id' => $account['id'],
                    'number' => $account['accountNumber'],
                    'holder' => $account['accountHolder'],
                    'bsb' => $account['bsb'],
                    'balance' => $account['balance'],
                    'available' => $account['available'],
                    'type' => $account['accountType'],
                ]);
                $old_account->save();
            }
        }

    }
}
