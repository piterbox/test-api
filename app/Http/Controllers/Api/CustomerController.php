<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\CustomerAccount;
use App\DailyBalance;
use App\Errors\CustomerError;
use App\Errors\MainError;
use App\Http\Controllers\Controller;
use App\Traits\Decryptor;
use App\Traits\Transformer;
use App\Transformers\CustomerAccountTransformer;
use App\Transformers\DailyBalanceTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Type;


class CustomerController extends Controller
{
    use Decryptor;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function accounts(Request $request)
    {
        $customer = $this->getCustomerByRequest($request);
        if(!$customer instanceof Customer)return $customer;

        $accounts = $customer->accounts;

        try{
            $new_key = $this->generateKey();
            $customer->encryption_key = $new_key;
            $customer->save();
            return response()->json([
                'accounts' => $accounts,
                'customer' => [
                    'customerId' => $customer->id,
                    'encryptionKey' => $new_key
                ]
            ]);
        }catch(\Exception $e){
            return response()->json(['error_code' => $e->getCode(), 'error_message' => $e->getMessage()]);
        }
    }

    public function data(Request $request)
    {
        $customer = $this->getCustomerByRequest($request);
        if(!$customer instanceof Customer)return $customer;

        $account_ids = $request->get('accounts', []);
        if(!$account_ids || count($account_ids) == 0) return response()->json(MainError::getError('bad_request'));

        $accounts = $customer->accounts()->whereIn('id', $account_ids)->get();
        $accounts_arr = [];

        if(!$accounts->isEmpty()){
            foreach ($accounts as $account){
                $transactions = Transformer::transformCollection($account->getTransactionsByDaysAgo($request->get('requestNumDays', 0)));
                $current_balance = DailyBalance::where('account_id', $account->id)->orderByDesc('date')->first();
                $day_end_balance = DailyBalanceTransformer::transform($current_balance);
                $account_transformed = CustomerAccountTransformer::transform($account);
                $account_transformed['transactions'] = $transactions;
                $account_transformed['dayEndBalance'] = $day_end_balance;
                $accounts_arr[] = $account_transformed;
            }
        }

        try{
             $new_key = $this->generateKey();
            $customer->encryption_key = $new_key;
            $customer->save();

            return response()->json([
                'bankData' => [
                    'bankName' => 'Bank of Statements',
                    'bankSlug' => 'Bank_of_Statements'
                ],
                'bankAccounts' => $accounts_arr,
                'customer' => [
                    'customerId' => $customer->id,
                    'encryptionKey' => $new_key
                ]
            ]);

        }catch(\Exception $e){
            return response()->json(['error_code' => $e->getCode(), 'error_message' => $e->getMessage()]);
        }
    }

    protected function getCustomerByRequest(Request $request)
    {
        $customer_id = $request->get('customerId');

        if(!$customer = Customer::find($customer_id)){
            return response()->json(MainError::getError('not_found'));
        }

        if(!$customer->isValidKey($request->get('encryptionKey'))){

            return response()->json(CustomerError::getError('error_encryption_key'));
        }

        return $customer;
    }


}
