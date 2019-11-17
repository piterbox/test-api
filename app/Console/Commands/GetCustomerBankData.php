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

class GetCustomerBankData extends Command
{
    protected $signature = 'customer-bank-data:get';
    protected $description = 'Getting customer\'s information about bank transactions';
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
                'accounts' => $customer->accounts->pluck('id')->get(),
                'requestNumDays' => env('NUM_DAYS_CUSTOMER_TRANSACTIONS', 30),
            ];

            $request = Request::create(
                env('CUSTOMER_DATA_URL', 'http://test-api.com/customer/data'),
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

            //update accounts
            if(!isset($data['bankData']['accounts']) || count($data['bankData']['accounts']) == 0) continue;

            $accounts = $data['bankData']['accounts'];

            foreach ($accounts as $account){
                $old_account = CustomerAccount::find($account['id']);
                if(!$old_account) continue;

                //update dailyBalances
                $daily_balance = DailyBalance::firstOrNew([
                    'date' => $account['dayEndBalances']['date'],
                    'balance' => $account['dayEndBalances']['balance'],
                    'account_id' => $old_account->id
                ]);
                $daily_balance->save();
                if(!isset($account['transactions']) || count($account['transactions']) == 0) continue;
                //update transactions
                foreach ($account['transactions'] as $transaction){
                    $old_transaction = Transaction::firstOrNew([
                        'id' => $transaction['id'],
                        'type' => $transaction['type'],
                        'date' => $transaction['date'],
                        'amount' => $transaction['amount'],
                        'text' => $transaction['text'],
                        'balance' => $transaction['balance'],
                        'tags' => $transaction['tags'],
                        'account_id' => $old_account->id
                    ]);
                    $old_transaction->save();
                    //write log about success updating
                }

            }
        }

    }
}
