<?php

namespace App\Http\Controllers\Checkout;

use App\Models\Area;
use App\Models\Governorate;
use App\Models\PaymentMethods;
use App\Models\VendorAreaDeliveryCharges;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CartStorage;
use App\Models\GiftCartStorage;
use App\Models\UserAddress;
use Auth;
use App\Models\ShippingMethods;
use App\Models\CustomerGiftCards;
use App\Models\Coupon;
use App\Models\Settings;
use App\Models\ProductsTogetherPrice;
use Illuminate\Support\Facades\Hash;
use App\Models\GuestUsers;

class CheckoutController extends Controller
{
    public function MyCart()
    {
        // session('couponCode','');
        $settings = Settings::find(1);
        if (!session('cartId')) {
            return redirect(route('home'));
        }
        $cart  = CartStorage::where('cart_id', session('cartId'))->first();
         $data = json_decode($cart->cart, true);
        $out = [];

        foreach($data as $element) {
            $out[$element['product']['vendor_id']][] = ['item' =>  $element];
            $out[$element['product']['vendor_id']]['vendor'] = ['vendor' =>  $element['product']['vendor']];
        }
        $orderTotal = 0 ;
        foreach($data as $item)
        {

            $orderTotal+=$item['total'];

        }

        // dd(view()->shared('settings'));
        $enableOrder = true;
        $min_order = 0;

        if($orderTotal < $min_order)
        {
            $enableOrder = false;
        }

        $groupedcart = json_encode($out);
        $coupon =  'Please';
        $discountamount = 0 ;
        $discount = Coupon::where('code',session('couponCode'))->where('status', 1)->first();
        if ($discount ) {
            if ($discount->is_fixed) {
                $discountamount = $discount->fixed;

            } else {

                $amount = $orderTotal * $discount->percentage / 100;
                $discountamount = $amount;

            }
        }
        $discountamount =number_format((float)($discountamount ), 2, '.', '');
        return view('checkout.cart')->with(compact('groupedcart', 'settings' ,'coupon' ,'min_order' ,'discountamount','enableOrder'));
    }
    public function checkout(Request $request)
    {
        if (!session('cartId')) {
            return redirect('home');
        }
        $countries = \App\Models\Countries::all();
        $governates = \App\Models\Governorate::all();
        $areas = \App\Models\Area::all();
        $payment_methods = PaymentMethods::where('active',1)->get();
        $cartItems = new \stdClass();

        $cartTotal = 0;

        if (CartStorage::where('cart_id', session('cartId'))->first()) {
            $cartItems = json_decode(CartStorage::where('cart_id', session('cartId'))->first()->cart);
        }

        $user = Auth::User();
        $userAddress = new \stdClass();
        if($user)
        {
            $userAddress = UserAddress::where('user_id' , Auth::Id())->with('countries' , 'governorate' ,'area')->get();
        }
            $delivery_charges =0;
            $coupon = session('couponCode') ? session('couponCode') : 'Please';
            $cartStorage = CartStorage::where('cart_id', session('cartId'))->first();
            $productCategories = [] ;
            $data = json_decode($cartStorage->cart, true);
            $enableOrder = true;
            $orderTotal = 0 ;
            $min_order = 0;

            foreach($data as $item)
            {
                $pItem  = \App\Models\Product::whereId($item['product']['id'])->with('vendor', 'offer','categories')->first();
                // dump($item['product'] );
                // dump($item['quantity'] );
                foreach($pItem->categories as $pcategory)
                {

                    if($pcategory['id'] == 21)
                    {
                        if($item['quantity']>view()->shared('settings')->max_palms)
                        {
                            $delivery_charges+=  view()->shared('settings')->delivery_charges_palms*(ceil($item['quantity'] / view()->shared('settings')->max_palms ));
                        }
                        else
                        {
                        $delivery_charges+=0;
                        }

                    }
                    else if($pcategory['id'] == 52)
                    {

                        if($item['quantity']>view()->shared('settings')->max_sheeps)
                        {
                            $delivery_charges+=view()->shared('settings')->delivery_charges_sheeps *(ceil($item['quantity'] / view()->shared('settings')->max_sheeps ));
                        }
                        else
                        {
                        $delivery_charges+=0;
                        }


                     }
                    else if($pcategory['id'] == 53)
                    {
                        if($item['quantity']>view()->shared('settings')->max_cows)
                        {

                            $delivery_charges+=view()->shared('settings')->delivery_charges_cows *(ceil($item['quantity'] / view()->shared('settings')->max_cows ));
                        }
                        else
                        {
                        $delivery_charges+=0;
                        }

                    }

                }
                $orderTotal+=$item['total'];
            }
            if($orderTotal < $min_order)
            {
                $enableOrder = false;
            }

            return view('checkout.guestCheckout')->with(compact('coupon','cartItems' ,'delivery_charges' , 'countries','governates','areas' ,'userAddress' ,'payment_methods'));


    }
    public function applyDiscount(Request $request)
    {
        $today = Carbon::today()->format('Y-m-d');
        $cart = CartStorage::where('cart_id', session('cartId'))->first();

        $itemsQuantity = array();
        foreach (json_decode($cart->cart) as $item) {
            $itemsQuantity[$item->product_id] = $item->quantity;
        }
        $totalAfterGroup = 0;
        $productWithPrices = ProductsTogetherPrice::whereIn('product_id', array_keys($itemsQuantity))->orWhereIn('with_product_id', array_keys($itemsQuantity))->get();
        foreach ($productWithPrices as $price) {
            if (array_key_exists($price->product_id, $itemsQuantity) && array_key_exists($price->with_product_id, $itemsQuantity)) {
                $quantity = (min($itemsQuantity[$price->product_id], $itemsQuantity[$price->with_product_id]));
                $totalAfterGroup += $quantity * $price->price;
                $itemsQuantity[$price->product_id] = $itemsQuantity[$price->product_id] - $quantity;
                $itemsQuantity[$price->with_product_id] = $itemsQuantity[$price->with_product_id] - $quantity;
            }
        }
        foreach (json_decode($cart->cart) as $item) {

            $totalAfterGroup += $itemsQuantity[$item->product_id] * $item->price;
        }
        $totalAfterGroup = number_format((float)$totalAfterGroup, 2, '.', '');

        $total = $totalAfterGroup;


        $giftcard = CustomerGiftCards::where('card', $request->discount_code)->where('is_used', 0)->where('email', Auth::user()->email)->first();

        if ($giftcard) {
            $grand_total = $request->total - $giftcard->amount + $request->shippingcharges;
        }
        $giftcard = Coupon::where('code', $request->discount_code)->where('status', 1)->where('from', '<=', $today)->where('to', '>=', $today)->first();

        if ($giftcard && $total >= $giftcard->minimum_order) {
            if ($giftcard->is_fixed) {
                $grand_total =  $total - ($giftcard->fixed  + $request->shippingcharges);
            } else {
                $amount = $request->total * $giftcard->percentage / 100;
                $grand_total = $total - $amount + $request->shippingcharges;
            }
            return [
                'success' => true,
                'message' => 'coupun applied with ' . ($giftcard->is_fixed == 1 ? $giftcard->fixed . ' KD' : $giftcard->percentage . '%'),
                'total' => $grand_total
            ];
        }

        return [
            'success' => false,
            'message' => 'coupon is not valid',
            'total' => ($total + $request->shippingcharges),

        ];
    }

    public function addCheckoutAddress(Request $request)
    {
        $user = GuestUsers::where('email' , $request->email)->first();

        if(!$user) {
            $user = GuestUsers::updateOrCreate(['email' => $request->email], [
                'email' => $request->email
                , 'first_name' => $request->first_name
                , 'last_name' => $request->last_name
                , 'name' => $request->first_name . ' ' . $request->last_name

            ]);
        }
        $userAddress = UserAddress::updateOrCreate([
            'address_type'=> 2,
            'first_name'=> $request->first_name,
            'second_name'=>$request->last_name ,
            'country_id'=>$request->country  ,
            'governate_id'=>$request->governate_id  ,
            'user_id'=>$user->id ,
            'city'=>$request->city ,
            'block'=>$request->block ,
            'street'=>$request->street ,
            'avenue'=>$request->avenue ,
            'floor'=>$request->floor ,
            'flat'=>$request->flat ,
            'extra_direction'=>$request->extra_direction ,
            'governate_id'=>$request->governate_id ,
            'area_id'=>$request->area_id ,
            'is_guest' => 1,
            'phone_no'=>$request->mobile,
            'fax'=>$request->fax,
        ]);
        $userAddress = UserAddress::whereId($userAddress->id)->with('countries' , 'governorate')->first();
        return  ($userAddress);
    }
    public function getGovernate(Request $request)
    {
        $governates = Governorate::where('country_id' , $request->country_id)->get();
        return $governates;
    }
    public function getAreas(Request $request)
    {

        $areas = Area::where('governorate_id' , $request->governorate_id)->get();

        return $areas;
    }
    public function checkVendorArea(Request $request)
    {

        $cart  = CartStorage::where('cart_id', session('cartId'))->first();
        $carttotal = (json_decode($cart->cart));
        $delivery_charges =0;
        $vendorAreas = Array();
        $totalDelivdryCharge = 0;
        // foreach (json_decode($cart->cart) as $item) {
        //     $vendorDeliveryCharges = VendorAreaDeliveryCharges::where('vendor_id',$item->product->vendor->id)->pluck('delivery_charge' , 'area_id');

        //     if(array_key_exists($request->area_id,$vendorDeliveryCharges->toArray() ))
        //     {
        //         $vendorAreas[$item->product->vendor->id]= $vendorDeliveryCharges[$request->area_id];
        //     }
        //     else
        //     {
        //         return json_encode(['status'=>false, 'product'=>json_encode($item->product)]);
        //     }
        // }
        $data = json_decode($cart->cart, true);
        $enableOrder = true;
        $orderTotal = 0 ;
        $min_order =0;
        foreach($data as $item)
        {
                $pItem  = \App\Models\Product::whereId($item['product']['id'])->with('vendor', 'offer','categories')->first();
                // dump($item['product'] );
                // dump($item['quantity'] );
                foreach($pItem->categories as $pcategory)
                {
                if($pcategory['id'] == 21)
                {
                    if($item['quantity']>view()->shared('settings')->max_palms)
                    {
                        $delivery_charges+=  view()->shared('settings')->delivery_charges_palms*(ceil($item['quantity'] / view()->shared('settings')->max_palms ));
                    }
                    else
                    {
                    $delivery_charges+=view()->shared('settings')->delivery_charges_palms;
                    }
                }
                else if($pcategory['id'] == 52)
                {
                    if($item['quantity']>view()->shared('settings')->max_sheeps)
                    {
                        $delivery_charges+=view()->shared('settings')->delivery_charges_sheeps *(ceil($item['quantity'] / view()->shared('settings')->max_sheeps ));
                    }
                    else
                    {
                    $delivery_charges+=view()->shared('settings')->delivery_charges_sheeps;
                    }

                 }
                else if($pcategory['id'] == 53)
                {
                    if($item['quantity']>view()->shared('settings')->max_cows)
                    {
                        $delivery_charges+=view()->shared('settings')->delivery_charges_cows *(ceil($item['quantity'] / view()->shared('settings')->max_cows ));
                    }
                    else
                    {
                    $delivery_charges+=view()->shared('settings')->delivery_charges_cows;
                    }
                }

            }
            $orderTotal+=$item['total'];
        }

        return json_encode(['status'=>true,'totalDelivery'=>number_format((float)($delivery_charges), 2, '.', ''),'total'=>number_format((float)( ($delivery_charges)+$orderTotal), 2, '.', '')]);

    }

    public function checkRegisteredAreaAddress(Request $request)
    {
        $address = UserAddress::whereId($request->address_id)->with('countries','governorate')->first();
        $cart  = CartStorage::where('cart_id', session('cartId'))->first();
        $carttotal = (json_decode($cart->cart));
        $vendorAreas = Array();
        $totalDelivdryCharge = 0;
        // foreach (json_decode($cart->cart) as $item) {
        //     $vendorDeliveryCharges = VendorAreaDeliveryCharges::where('vendor_id',$item->product->vendor->id)->pluck('delivery_charge' , 'area_id');

        //     if(array_key_exists($address->area_id,$vendorDeliveryCharges->toArray() ))
        //     {
        //         $vendorAreas[$item->product->vendor->id]= $vendorDeliveryCharges[$address->area_id];
        //     }
        //     else
        //     {
        //         return json_encode(['status'=>false, 'product'=>json_encode($item->product)]);
        //     }
        // }
        $delivery_charges =0;
        $data = json_decode($cart->cart, true);
        $enableOrder = true;
        $orderTotal = 0 ;
        $min_order =0;
        foreach($data as $item)
        {
                $pItem  = \App\Models\Product::whereId($item['product']['id'])->with('vendor', 'offer','categories')->first();
                // dump($item['product'] );
                // dump($item['quantity'] );
                foreach($pItem->categories as $pcategory)
                {
                if($pcategory['id'] == 21)
                {
                    if($item['quantity']>view()->shared('settings')->max_palms)
                    {
                        $delivery_charges+=  view()->shared('settings')->delivery_charges_palms*(ceil($item['quantity'] / view()->shared('settings')->max_palms ));
                    }
                    else
                    {
                    $delivery_charges+=view()->shared('settings')->delivery_charges_palms;
                    }
                }
                else if($pcategory['id'] == 52)
                {
                    if($item['quantity']>view()->shared('settings')->max_sheeps)
                    {
                        $delivery_charges+=view()->shared('settings')->delivery_charges_sheeps *(ceil($item['quantity'] / view()->shared('settings')->max_sheeps ));
                    }
                    else
                    {
                    $delivery_charges+=view()->shared('settings')->delivery_charges_sheeps;
                    }

                 }
                else if($pcategory['id'] == 53)
                {
                    if($item['quantity']>view()->shared('settings')->max_cows)
                    {
                        $delivery_charges+=view()->shared('settings')->delivery_charges_cows *(ceil($item['quantity'] / view()->shared('settings')->max_cows ));
                    }
                    else
                    {
                    $delivery_charges+=view()->shared('settings')->delivery_charges_cows;
                    }                    }

            }
            $orderTotal+=$item['total'];
        }
        $grand_total = $orderTotal;
                $today = \Carbon\Carbon::today()->format('Y-m-d');
        $discount = \App\Models\Coupon::where('code',session('couponCode'))->where('status', 1)->where('from', '<=', $today)->where('to', '>=', $today)->first();
            $totalDiscount = 0 ;


        if ($discount ) {
            if ($discount->is_fixed) {

                $grand_total =  $orderTotal - ($discount->fixed  );
                $discount = $discount->fixed ;
            } else {

                $amount = $orderTotal * $discount->percentage / 100;
                $grand_total = $orderTotal - $amount ;
                $discount = $orderTotal * $discount->percentage / 100;
            }

        }
        return json_encode(['status'=>true,'totalDelivery'=>number_format((float)(($delivery_charges)), 2, '.', ''),'total'=>number_format((float)(($grand_total>0 ?$grand_total : 0)+$delivery_charges), 2, '.', ''),'address'=>$address,'user_id'=>Auth::Id()]);

    }
}
