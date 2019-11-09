<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Users\DepositController;
use App\Http\Helpers\Common;
use App\Models\Currency;
use App\Models\CurrencyPaymentMethod;
use App\Models\FeesLimit;
use App\Models\Merchant;
use App\Models\MerchantPayment;
use App\Models\RequestPayment;
use App\Models\PaymentMethod;
use App\Models\Preference;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use CoinPayment;
use Hexters\CoinPayment\Entities\cointpayment_log_trx;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Omnipay\Omnipay;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Exception\PayPalConnectionException;

class OutPaymentController extends Controller
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new Common();
    }

    public function index(Request $request)
    {
        // dd($request->all());

        setActionSession();

        $id = $request->id;

        $data['requestPayment'] = $requestPayment = RequestPayment::with([
            'currency:id,symbol,code',
            'user:id,email,first_name,last_name'
        ])->where(['id' => $id])->first();
        $data['transfer_fee']   = $transfer_fee   = FeesLimit::where([
            'transaction_type_id' => Request_To, 
            'currency_id' => $requestPayment->currency_id
        ])->first(['charge_percentage', 'charge_fixed']);


        if (!$requestPayment)
        {
            $this->helper->one_time_message('error', __('Transaction was not found!'));
            return redirect('outpayment/fail');
        }
        //For showing the message that merchant available or not
        $data['isPaymentAvailable'] = true;
        if (!$requestPayment)
        {
            $data['isPaymentAvailable'] = false;
        }

        return view('outPayment.home', $data);
    }

    protected function setDefaultSessionValues()
    {
        $preferences = Preference::where('field', '!=', 'dflt_lang')->get();
        if (!empty($preferences))
        {
            foreach ($preferences as $pref)
            {
                $pref_arr[$pref->field] = $pref->value;
            }
        }
        if (!empty($preferences))
        {
            Session::put($pref_arr);
        }

        // default_currency
        $default_currency = Setting::where('name', 'default_currency')->first(['value']);
        if (!empty($default_currency))
        {
            Session::put('default_currency', $default_currency->value);
        }

        //default_timezone
        $default_timezone = User::with(['user_detail:id,user_id,timezone'])->where(['id' => auth()->user()->id])->first(['id'])->user_detail->timezone;
        if (!$default_timezone)
        {
            Session::put('dflt_timezone_user', session('dflt_timezone'));
        }
        else
        {
            Session::put('dflt_timezone_user', $default_timezone);
        }

        // default_language
        $default_language = Setting::where('name', 'default_language')->first(['value']);
        if (!empty($default_language))
        {
            Session::put('default_language', $default_language->value);
        }

        // company_name
        $company_name = Setting::where('name', 'name')->first(['value']);
        if (!empty($company_name))
        {
            Session::put('name', $company_name->value);
        }

        // company_logo
        $company_logo = Setting::where(['name' => 'logo', 'type' => 'general'])->first(['value']);
        if (!empty($company_logo))
        {
            Session::put('company_logo', $company_logo->value);
        }
    }
    /*System Merchant Payment ends*/

    /*PayUMoney Merchant Payment Starts*/
    public function cardconnect(Request $request)
    {

        $payment_method     = PaymentMethod::where(['status' => 'Active', 'name' => 'CardConnect'])->first(['id', 'name']);
        if($payment_method) 
        {
           
            Session::put('payment_method_id', $payment_method->id);
            session(['transInfo' => $request->all()]);
            return view('outPayment.cardconnect');
        } 
        else
        {
            return redirect()->back();
        }
    }

    public function cardconnectPaymentStore(Request $request)
    {
        actionSessionCheck();

        $validation = Validator::make($request->all(), [
            'cardToken' => 'required',
            'expiry' => 'required',
            'cvvc' => 'required'
        ]);
        if ($validation->fails())
        {
            return redirect()->back()->withErrors($validation->errors());
        }

        $payment_method_id         = Session::get('payment_method_id');
        $currencyId                =  Session::get('currency_id');
        $sessionValue = session('transInfo');

        if ($_POST && isset($request->cardToken))
        {
            $currencyPaymentMethod = CurrencyPaymentMethod::where(['currency_id' => $sessionValue['currency_id'], 'method_id' => $payment_method_id])->where('activated_for', 'like', "%deposit%")->first(['method_data']);
            $methodData            = json_decode($currencyPaymentMethod->method_data);
            $merchant_id           = $methodData->merchant_id;
            $public_key            = $methodData->public_key;

            // Site's REST URL
            $url = 'https://fts.cardconnect.com:6443/cardconnect/rest/';

            $public_key = 'Basic {'.$public_key.'}';
            $client = new \App\libraries\CardConnectRestClient($url, $public_key);

            $tempAmount = $sessionValue['amount'] * 100;
            $newRequest = array(
                'merchid'   => $merchant_id,
                'account'   => $request->cardToken,
                'amount'    => $tempAmount,
                'ecomind'   => "E",
                'capture'   => "y",
                "expiry"    => $request->expiry
            );
            $response = $client->authorizeTransaction($newRequest);

            if ($response->getStatusCode() == "200")
            {
                
                $content = json_decode($response->getBody()->read(1024));

                if ($content->token)
                {
                    // dd($sessionValue);

                    $RequestPaymentId    = $sessionValue['id'];
                    
                    try
                    {
                        \DB::beginTransaction();

                        $RequestPayment                = RequestPayment::with(['user:id,first_name,last_name,phone,carrierCode,email', 'receiver:id,first_name,last_name', 'currency:id,symbol,code'])->find($RequestPaymentId);
                        $RequestPayment->accept_amount = $sessionValue['amount'];
                        $RequestPayment->status        = "Success";
                        $RequestPayment->save();

                        //Update Request Creator Transaction Information
                        $FeesLimit                        = FeesLimit::where(['currency_id' => $sessionValue['currency_id'], 'transaction_type_id' => Request_To])->first(['charge_percentage', 'charge_percentage']);
                        $transaction_C                    = Transaction::where(['user_id' => $RequestPayment->user_id, 'currency_id' => $sessionValue['currency_id'], 'transaction_reference_id' => $RequestPayment->id, 'transaction_type_id' => Request_From])->first(['id', 'percentage', 'charge_percentage', 'charge_percentage', 'subtotal', 'total', 'status']);
                        $transaction_C->percentage        = 0;
                        $transaction_C->charge_percentage = 0;
                        $transaction_C->charge_fixed      = 0;
                        $transaction_C->subtotal          = $sessionValue['amount'];
                        $t_total                          = $transaction_C->subtotal;
                        $transaction_C->total             = $t_total;
                        $transaction_C->status            = 'Success';
                        $transaction_C->save();

                        //Update Request Acceptor Transaction Information
                        $transaction_A = Transaction::where(['user_id' => $RequestPayment->receiver_id, 'currency_id' => $sessionValue['currency_id'], 'transaction_reference_id' => $RequestPayment->id, 'transaction_type_id' => Request_To])->first(['id', 'percentage', 'charge_percentage', 'charge_percentage', 'subtotal', 'total', 'status']);

                        $transaction_A->percentage        = @$FeesLimit->charge_percentage ? @$FeesLimit->charge_percentage : 0;
                        $transaction_A->charge_percentage = $sessionValue['percentage_fee'];
                        $transaction_A->charge_fixed      = $sessionValue['fixed_fee'];
                        $transaction_A->subtotal          = $sessionValue['amount'];
                        $t_total                          = $transaction_A->subtotal + ($transaction_A->charge_percentage + $transaction_A->charge_fixed);
                        $transaction_A->total             = '-' . $t_total;
                        $transaction_A->status            = 'Success';
                        $transaction_A->save();

                        //Update Request Creator Wallet
                        $RequestSenderWallet = Wallet::where(['user_id' => $RequestPayment->user_id, 'currency_id' => $sessionValue['currency_id']])->first(['id', 'balance']);
                        if (!empty($RequestSenderWallet))
                        {
                            $RequestSenderWallet->balance = $RequestSenderWallet->balance + $sessionValue['amount'];
                            $RequestSenderWallet->save();
                        }
                        else
                        {
                            $creatorWallet              = new Wallet();
                            $creatorWallet->balance     = $sessionValue['amount'];
                            $creatorWallet->user_id     = $RequestPayment->user_id;
                            $creatorWallet->currency_id = $sessionValue['currency_id'];
                            $creatorWallet->is_default  = 'No';
                            $creatorWallet->save();
                        }

                        \DB::commit();

                        clearActionSession();
                        return redirect('outpayment/success');
                    }
                    catch (\Exception $e)
                    {
                        \DB::rollBack();
                        clearActionSession();
                        $this->helper->one_time_message('error', $e->getMessage());
                        return redirect('outpayment/fail');
                    }
                    
                }
                else
                {
                    $this->helper->one_time_message('error', __($content->resptext.'!'));
                    return back();
                }
            }
            else
            {
                $message = $response->getMessage();
                $this->helper->one_time_message('error', $message);
                return back();
            }
        }
        else
        {
            $this->helper->one_time_message('error', __('Please try again later!'));
            return back();
        }
        
    }
    /* End of CardConnect */

    //fixed in pm_v2.3
    public function merchantPayumoneyPaymentFail(Request $request)
    {
        if ($_POST['status'] == 'failure')
        {
            clearActionSession();
            $this->helper->one_time_message('error', __('You have cancelled your payment'));
            return redirect('/');
        }
    }
    /*PayUMoney Merchant Payment Ends*/

    public function success()
    {
        $data['amount']        = Session::get('merchant_amount');
        $data['currency_code'] = Session::get('merchant_currency_code');
        return view('merchantPayment.success', $data);
    }

    public function fail()
    {
        // dd('merchantPayment.fail');
        return view('merchantPayment.fail');
    }

    /**
     * [Extended Function] - Checks Deposit Fees Of each Payment Method(if fees limit is active) with Merchant fee - starts
     */
    protected function checkDepositFeesPaymentMethod($currencyId, $paymentMethodId, $amount, $merchantFee)
    {
        $feeInfo = FeesLimit::where(['transaction_type_id' => Deposit, 'currency_id' => $currencyId, 'payment_method_id' => $paymentMethodId])
            ->first(['charge_percentage', 'charge_fixed', 'has_transaction']);
        if ($feeInfo->has_transaction == "Yes")
        {
            //if fees limit is not active, both merchant fee and deposit fee will be added
            $feeInfoChargePercentage          = @$feeInfo->charge_percentage;
            $feeInfoChargeFixed               = @$feeInfo->charge_fixed;
            $depositCalcPercentVal            = $amount * (@$feeInfoChargePercentage / 100);
            $depositTotalFee                  = $depositCalcPercentVal+@$feeInfoChargeFixed;
            $merchantCalcPercentValOrTotalFee = $amount * ($merchantFee / 100);
            $totalFee                         = $depositTotalFee + $merchantCalcPercentValOrTotalFee;
        }
        else
        {
            //if fees limit is not active, only merchant fee will be added
            $feeInfoChargePercentage          = 0;
            $feeInfoChargeFixed               = 0;
            $depositCalcPercentVal            = 0;
            $depositTotalFee                  = 0;
            $merchantCalcPercentValOrTotalFee = $amount * ($merchantFee / 100);
            $totalFee                         = $depositTotalFee + $merchantCalcPercentValOrTotalFee;
        }
        $data = [
            'feeInfoChargePercentage'          => $feeInfoChargePercentage,
            'feeInfoChargeFixed'               => $feeInfoChargeFixed,
            'depositCalcPercentVal'            => $depositCalcPercentVal,
            'depositTotalFee'                  => $depositTotalFee,
            'merchantCalcPercentValOrTotalFee' => $merchantCalcPercentValOrTotalFee,
            'totalFee'                         => $totalFee,
        ];
        return $data;
    }
    /**
     * [Extended Function] - ends
     */

 
}
