<?php
/**
 * Created by PhpStorm.
 * User: sh3ban
 * Date: 4/30/19
 * Time: 11:46 AM
 */
namespace App\Http\Controllers\Knet;


use App\Models\OrderTransactions;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Session;
use Carbon\Carbon;
use App\Mail\OrderMail;
use App\Mail\VendorOrderMail;
use App\Mail\AdminOrderMail;
use App\Models\CartStorage;

use Mail;

use Auth;

use App\Models\Order;

use App\Models\Settings;
use App\Mail\OrderTransactionMail;

class KnetController extends Controller
{

    //Redirect to Knet Payment Page with order detail
    public function redirect($order_id)
    {
        $order = Order::find($order_id);
        $TranAmount = $order->total;

        $TranportalId=env('KNET_Tranportal_ID');
        $ReqTranportalId="id=".$TranportalId;
        $TranportalPassword=env('KNET_Tranportal_Password');
        $ReqTranportalPassword="password=".$TranportalPassword;
        $ReqAmount="amt=".($TranAmount);
        $TranTrackid=mt_rand();
        $ReqTrackId="trackid=".$TranTrackid;
        $ReqCurrency="currencycode=414";
        $ReqLangid="langid=USA";
        $ReqAction="action=1";
        $ResponseUrl=env('APP_URL').env('KNET_SUCCESS_URL');
        $ReqResponseUrl="responseURL=".$ResponseUrl;
        $ErrorUrl=env('APP_URL').env('KNET_FAILURE_URL');
        $ReqErrorUrl="errorURL=".$ErrorUrl;

        $ReqUdf1="udf1=".$order_id;
        $ReqUdf2="udf2=udf2";


        $ReqUdf3="udf3=test3";
        $ReqUdf4="udf4=test4";
        $ReqUdf5="udf5=test5";

        $param=$ReqTranportalId."&".$ReqTranportalPassword."&".$ReqAction."&".$ReqLangid."&".$ReqCurrency."&".$ReqAmount."&".$ReqResponseUrl."&".$ReqErrorUrl."&".$ReqTrackId."&".$ReqUdf1."&".$ReqUdf2."&".$ReqUdf3."&".$ReqUdf4."&".$ReqUdf5;


        $termResourceKey=env('KNET_Terminal_Key');
        $param=$this->encryptAES($param,$termResourceKey)."&tranportalId=".$TranportalId."&responseURL=".$ResponseUrl."&errorURL=".$ErrorUrl;
 return ["params"=> $param , "knetUrl"=> env('KNET_URL')];
    }



    //Successfull transaction view
    public function success(Request $request)
    {
        $settings = Settings::find(1);
        $transData = $this->decrypt($request->trandata, env('KNET_Terminal_Key'));

        parse_str($transData, $output);
        if ($output['udf1'] != 'card') {

        $order = Order::find($output['udf1']);
        $orderTransaction = OrderTransactions::firstOrCreate(['order_id' => $output['udf1'],
            'payment_id' => $output['paymentid'],
            'result' => $output['result'],
            'auth' => $output['auth'],
            'reference' => $output['ref'],
            'track_id' => $output['trackid'] ? $output['trackid'] : 0,
            'tran_id' => isset($output['tranid']) ? $output['tranid'] : 0,
            'amount' => $output['amt'],
            'currency' => 414,
            'time' => Carbon::now()->format('h:s:i')]);



                if ($order->is_guest == 1) {
                    Mail::to($order->guestusers->email)->send(new OrderTransactionMail($order->guestusers, $order, $orderTransaction, $settings));
                } else {
                    Mail::to($order->user->email)->send(new OrderTransactionMail($order->user, $order, $orderTransaction, $settings));
                }


    }

        if ($output['result'] != 'CAPTURED') {
            return view('knet.failure')->with(compact('settings', 'orderTransaction' ,'order'));
        }
if ($output['result'] == 'CAPTURED') {
            $order->is_paid = 1;
        $order->update();
    $orderemail = Order::with('status', 'ordertrack.orderstatus','ordertransactions','orderproducts','user')->where('id',($output['udf1']))->first();
                CartStorage::where('cart_id', session('cartId'))->delete();
            $request->session()->forget('cartId');
        $this->customerOrderEmail($orderemail, 'Order Confirmation');
        $this->adminOrderEmail($orderemail, 'New Order Submited');
        $this->deliveryOrderEmail($orderemail, 'New Order To Deliver');
        $this->vendorOrderEmail($orderemail, 'You Have New Order');


}
//        $orderTransaction = OrderTransactions::whereId($orderTransaction->id)->exclude()->toArray();

        return view('knet.success')->with(compact( 'settings' , 'orderTransaction' ,'order'));
    }




    //Built in KNet Methods
    //AES Encryption Method Starts
    function encryptAES($str,$key) {
        $str = $this->pkcs5_pad($str);
        $encrypted = openssl_encrypt($str, 'AES-128-CBC', $key, OPENSSL_ZERO_PADDING, $key);
        $encrypted = base64_decode($encrypted);
        $encrypted=unpack('C*', ($encrypted));
        $encrypted=$this->byteArray2Hex($encrypted);
        $encrypted = urlencode($encrypted);
        return $encrypted;
    }

    function pkcs5_pad ($text) {
        $blocksize = 16;
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    function byteArray2Hex($byteArray) {
        $chars = array_map("chr", $byteArray);
        $bin = join($chars);
        return bin2hex($bin);
    }
    function hex2ByteArray($hexString) {
        $string = hex2bin($hexString);
        return unpack('C*', $string);
    }
    function byteArray2String($byteArray) {
        $chars = array_map("chr", $byteArray);
        return join($chars);
    }
    function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }
    function decrypt($code,$key) {
        $code =  $this->hex2ByteArray(trim($code));
        $code=   $this->byteArray2String($code);
        $iv = $key;
        $code = base64_encode($code);
        $decrypted = openssl_decrypt($code, 'AES-128-CBC', $key, OPENSSL_ZERO_PADDING, $iv);
        return $this->pkcs5_unpad($decrypted);
    }
//AES Encryption Method Ends

    protected function customerOrderEmail($order, $title)
    {
        if ($order->is_guest == 0) {
            $user = $order->user;
        } else {
            $user = $order->guestusers;
        }
        retry(5, function () use ($order, $user, $title) {
            Mail::to($user)->send(new OrderMail($order, $user, $title));
        }, 100);

        return true;
    }

    protected function adminOrderEmail($order, $title)
    {
        $sales_email = app('settings')->email_sales;

        if (!$sales_email) {
            return;
        }

        retry(5, function () use ($order, $sales_email, $title) {
            Mail::to($sales_email)->send(new AdminOrderMail($order, $title));
        }, 100);

        return true;
    }

    protected function deliveryOrderEmail($order, $title)
    {
        $sales_email = app('settings')->email_support;

        if (!$sales_email) {
            return;
        }

        retry(5, function () use ($order, $sales_email, $title) {
            Mail::to($sales_email)->send(new AdminOrderMail($order, $title));
        }, 100);

        return true;
    }

    public function vendorOrderEmail($order, $title)
    {
        foreach ($order->orderproducts as $product) {
            $user = $product->vendor;
            retry(5, function () use ($order, $user, $title) {
                Mail::to($user)->send(new VendorOrderMail($order, $user, $title));
            }, 100);
        }


        return true;
    }
}
