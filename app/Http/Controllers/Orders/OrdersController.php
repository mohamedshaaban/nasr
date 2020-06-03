<?php
/**
 * Created by PhpStorm.
 * User: sh3ban
 * Date: 3/27/19
 * Time: 2:12 PM
 */
namespace App\Http\Controllers\Orders;

use App\Models\OrderReturnItems;
use App\Models\VendorOrderDeliveryCharges;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CartStorage;
use App\Models\UserAddress;
use Auth;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStatus;
use App\Models\OrderTrack;
use App\Models\Product;
use App\Models\VendorAreaDeliveryCharges;
use App\Models\VendorCommissions;
use App\Mail\OrderMail;
use App\Mail\VendorOrderMail;
use App\Mail\AdminOrderMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Knet\KnetController;
class OrdersController extends Controller
{
    public function placeOrder(Request $request)
    {
        $cart     = CartStorage::where('cart_id', session('cartId'))->first();
        $order = new Order();
        $total = 0;
        if ($cart) {
$delivery_charges = 0 ;
            $orderCreatedStatus = OrderStatus::where('is_default', 1)->first();
            $cartItems     = json_decode($cart->cart);
            $itemsQuantity = array();
            foreach (json_decode($cart->cart) as $item) {
                $itemsQuantity[$item->product_id] = $item->quantity;
            }

            foreach (json_decode($cart->cart) as $item) {

                $total += $item->quantity * $item->price;
            }
            $total = number_format((float)$total, 2, '.', '');


            $order          = new Order();
            $address = UserAddress::find($request->address_id);


            if (Auth::user()) {
                $order->user_id          = Auth::Id();
                $order->is_guest  = 0;
            } else {
                $order->user_id          = $request->user_id;
                $order->is_guest  = 1;
            }
             $order->address_id       = $request->address_id;
            $order->unique_id        = substr(\md5(uniqid(5)), 0, 6);
            $order->order_date       = Carbon::now();
            $order->sub_total        = $total;
            $order->delivery_charges = 0;
            $order->total            = $total + $order->delivery_charges;
            $order->payment_method   = 2;
            $order->shipping_method  = 1;


            $order->order_requester = session('order_requester');
            $order->order_destination= session('order_destination');
            $order->order_category= session('order_category');
            $order->order_extra= session('order_extra');

            $order->save();

            $vendorTotalOrder = array();
            $vendorAreas = array();
            $totalDelivdryCharge = 0;

            $vendProducts = [];
            foreach ($cartItems as $item) {


                $product           = Product::find($item->product_id);
                $product->quantity = ($product->quantity - $item->quantity) < 0 ? 0 : ($product->quantity - $item->quantity ) ;
                $product->update();


                $orderProduct               = new OrderProduct();
                $orderProduct->order_id     = $order->id;
                $orderProduct->product_id   = $item->product_id;
                $orderProduct->quantity     = $item->quantity;
                $orderProduct->sub_total    = $item->price;
                $orderProduct->vendor_id    = $product->vendor_id;
                $orderProduct->extraoption    = isset($item->extraoption) ? $item->extraoption : '';
                $orderProduct->total        = $item->total;
                $orderProduct->save();

                if (!isset($vendorTotalOrder[$product->vendor_id])) {

                    $vendorTotalOrder[$product->vendor_id]  = $item->total;
                } else {
                    $vendorTotalOrder[$product->vendor_id]  = $vendorTotalOrder[$product->vendor_id]  + $item->total;
                }



                $vendorDeliveryCharges = VendorAreaDeliveryCharges::where('vendor_id', $item->product->vendor->id)->pluck('delivery_charge', 'area_id');

                if (array_key_exists($address->area_id, $vendorDeliveryCharges->toArray())) {
                    $vendorAreas[$item->product->vendor->id] =$delivery_charges;
                }
            }
            foreach ($vendorTotalOrder as $key => $vendorTotal) {
                $orderTack = new OrderTrack();
                $orderTack->order_id   = $order->id;
                $orderTack->order_status_id  = $orderCreatedStatus->id;
                $orderTack->vendor_id  = $key;
                $orderTack->save();
                $vendorCommission = VendorCommissions::where('vendor_id', $key)->first();
                $commission_kd =  @$vendorCommission->fixed;
                $commission_precentage = @$vendorCommission->precentage;
//                if ($order->payment_method == 1) {
//                    $commission_kd = ($vendorAreas[$key] + $vendorTotalOrder[$key]) * ($vendorCommission->precentage / 100);
//                    $commission_precentage = $vendorCommission->precentage;
//                }

            }
            $order->delivery_charges = array_sum($vendorAreas);
            $order->total = $order->total + array_sum($vendorAreas);
            $today = \Carbon\Carbon::today()->format('Y-m-d');
            $discount = \App\Models\Coupon::where('code', session('couponCode'))->where('status', 1)->where('from', '<=', $today)->where('to', '>=', $today)->first();

             if ($discount && is_null($discount->num_uses) )  {
                if ($discount->is_fixed) {

                    $grand_total =  $order->total  - ($discount->fixed);
                } else {

                    $amount = $order->total * $discount->percentage / 100;
                    $grand_total = $order->total  - $amount;
                }
                $order->coupon_id = $discount->id;
            }
            else if($discount&& !is_null($discount->num_uses)&&$discount->num_uses > 0)
            {

                 if ($discount->is_fixed) {

                    $grand_total =  $order->total  - ($discount->fixed);
                } else {

                    $amount = $order->total * $discount->percentage / 100;
                    $grand_total = $order->total  - $amount;
                }
                $order->coupon_id = $discount->id;
                $discount->num_uses = $discount->num_uses-1;
                $discount->update();
            }
            else {
                $grand_total = $order->total;
            }
            $grand_total = $grand_total > 0 ? $grand_total : 0;
            $order->total = $grand_total;
              if($order->total ==0)
            {
                $order->is_paid = 1 ;
            }
            $order->update();


            $request->session()->forget('couponCode');
        }



        if($request->orderpayment == 1 && $order->total > 0)
        {
            $knet = new KnetController();

            $knetRes = $knet->redirect($order->id );

            return ['status' => 'true', 'id' => $order->id, 'data'=>$knetRes ,'knetpayment'=>1];


        }
        CartStorage::where('cart_id', session('cartId'))->delete();
        $request->session()->forget('cartId');
//        $this->customerOrderEmail($order, 'Order Confirmation');
//        $this->adminOrderEmail($order, 'New Order Submited');
       // $this->deliveryOrderEmail($order, 'New Order To Deliver');
//        $this->vendorOrderEmail($order, 'You Have New Order');
        return json_encode(['status' => 'true', 'order' => $order->unique_id,'knetpayment'=>0]);
    }
    public function thankYou(Request $request)
    {
        $order = Order::where('unique_id', $request->order_id)->first();
        return view('customer.Orders.thankYou')->with(['order' => $order]);
    }
    public function orders(Request $request)
    {
        $orders = Order::where('user_id', Auth::Id())->with('status')->OrderBy('id', 'DESC')->paginate(15);
        return $orders;
    }

    protected function customerOrderEmail($order, $title)
    {
        if ($order->is_guest == 0) {
            $user = $order->user;
        } else {
            $user = $order->guestusers;
        }
        try {
            retry(5, function () use ($order, $user, $title) {
                Mail::to($user)->send(new OrderMail($order, $user, $title));
            }, 100);
        }
catch (\Exception $exception)
{

}

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


    public function deleteOrderItem(Request $request)
    {
        $orderProducts = OrderProduct::find($request->id);
       $orderReturnItm = OrderReturnItems::create([
            'product_id'=>$orderProducts->product_id,
            'order_id'=>$orderProducts->order_id,
            'quantity'=>$orderProducts->quantity,
            'status'=>0
        ]);
        return ($request->id);
    }
}
