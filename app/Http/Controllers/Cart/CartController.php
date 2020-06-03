<?php

namespace App\Http\Controllers\Cart;

use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductSizes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CartStorage;
use Illuminate\Support\Facades\Session;
use Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Coupon;

class CartController extends Controller
{
    public static function get(Request $request , $server = false )
    {
 // session('couponCode','');
// Session::forget('couponCode');
        $cartStorage = CartStorage::where('cart_id', session('cartId'))->first();
        if (!$cartStorage) {
            return;
        }
        $totalCart  = 0;
        $cartCount = 0;
        $cart = array();
        foreach (json_decode($cartStorage->cart) as $item) {
                if (!$item) {
                    continue;
                }
                $option_id = array();

                $price = $item->price;

                $product = \App\Models\Product::whereId($item->product_id)->first();
                $itemcart = array();
                if (isset($item->product_id)&&($product->quantity >= 1 && $product->in_stock==1 )) {

                    $itemcart['product_id'] = $item->product_id;
                    $itemcart['image'] = $item->image;
                    $itemcart['product'] = $item->product;


                    if (isset($request['params']['product'])&&$request['params']['product']['id'] == $item->product_id) {
                        if(isset($request['params']['extraoptionvalue'])&&$request['params']['extraoptionvalue']!=''&&$request['params']['extraoptionvalue']!=0)
                        {
                            $product = \App\Models\Product::whereId($request['params']['product']['id'])->with('vendor', 'offer','categories')->first();

                            $ProductSizes = ProductSizes::where('product_id', $product->id)->where('sizes_id',$request['params']['extraoptionvalue'])->first();
                            $price = $ProductSizes->price;
                            if ($product->offer) {

                                if ($product->offer->is_fixed) {

                                    $price = $price - $product->offer->fixed;
                                } else {

                                    $price = $price - (($price * $product->offer->percentage) / 100);
                                }
                                $price = $price > 0 ? $price : 0 ;

                            }

                        }
                        $itemcart['total'] = $price * $request['params']['quantity'];
                        $itemcart['price'] = $price;
                        $itemcart['quantity'] = $request['params']['quantity'];
                    } else {
                        $itemcart['total'] = $price * $item->quantity;
                        $itemcart['price'] = $price;
                        $itemcart['quantity'] = $item->quantity;
                    }

                        $itemcart['extraoption'] = $item->extraoption;




                    $totalCart += $itemcart['price'] * $itemcart['quantity'];
                    array_push($cart, $itemcart);

                }
            }

        $cartStorage->cart = json_encode($cart);
        $cartStorage->update();

        $cartStorage->refresh();
        foreach (json_decode($cartStorage->cart) as $item) {

            $totalCart += $item->total;
            $cartCount++;
        }

        $data = json_decode($cartStorage->cart, true);
        $out = [];

        foreach($data as $element) {
            $out[$element['product']['vendor_id']][] = ['item' =>  $element];
            $out[$element['product']['vendor_id']]['vendor'] = ['vendor' =>  $element['product']['vendor']];
        }
        $groupedcart = json_encode($out);
        if($server==true)
        {

            return ['cart' => $cartStorage->cart, 'cartId' => $cartStorage->cart_id, 'cartCount' => (($cartCount<0)? $cartCount : 0), 'totalCart' => $totalCart, 'groupedCart' => $groupedcart];
        }
        $today = Carbon::today()->format('Y-m-d');
        $discount = Coupon::where('code',session('couponCode'))->where('status', 1)->where('from', '<=', $today)->where('to', '>=', $today)->first();


        $discountamount = 0 ;
        if ($discount ) {
            if ($discount->is_fixed) {
                $discountamount =$discount->fixed;
                $grand_total =  $totalCart - ($discount->fixed  );
            } else {

                $amount = $request * $discount->percentage / 100;
                $discountamount = $amount;
                $grand_total = $totalCart - $amount ;
            }
        }
        else
        {
            $grand_total =$totalCart ;
        }
        $enableOrder = true;
        $orderTotal = 0 ;
        $min_order = 0;
        foreach($data as $item)
        {
            $orderTotal+=$item['total'];
        }
        if($orderTotal < $min_order)
        {
            $enableOrder = false;
        }
        return json_encode(['cart' => $cartStorage->cart, 'cartId' => $cartStorage->cart_id, 'cartCount' => (($cartCount>0)? $cartCount : 0), 'totalCart' => ($orderTotal>0) ? (number_format((float)($orderTotal), 3, '.', '')):0, 'groupedCart' => $groupedcart,'cartbedforeTotal'=>($orderTotal>0) ? (number_format((float)($orderTotal), 3, '.', '')):0,'discountamount'=>number_format((float)($discountamount), 3, '.', ''),'enableOrder'=>$enableOrder]);
    }

    public static function add(Request $request)
    {
        Session::forget('couponCode');
        $added = false;
        if (session('cartId') && CartStorage::where('cart_id', session('cartId'))->first()) {

            $cartStorage = CartStorage::where('cart_id', session('cartId'))->first();
            $create = false;
        } else {

            $cartStorage = new CartStorage();
            $create = true;
        }

        if (Auth::User()) {
            $cartStorage->user_id = Auth::Id();
        } else {
            $cartStorage->user_id = Str::random(10);
        }
        if (!session('cartId') ) {
            $cartStorage->cart_id = Str::random(10);
        } else {
            $cartStorage->cart_id = session('cartId');
        }
        $countItems = 0;
        $totalCart = 0;

        $cart = array();
        $productIds = array();

        if ($cartStorage->cart) {



            foreach (json_decode($cartStorage->cart) as $item) {
                if (!$item) {
                    continue;
                }
                $option_id = array();

                $price = $item->price;


                $itemcart = array();
                if (isset($item->product_id)) {
                    $itemcart['product_id'] = $item->product_id;
                    $itemcart['image'] = $item->image;
                    $itemcart['product'] = $item->product;



                        $itemcart['total'] = $price * $item->quantity;
                        $itemcart['price'] = $price;
                        $itemcart['quantity'] = $item->quantity;


                        $itemcart['extraoption'] = @$item->extraoption;


                    $countItems++;

                    $totalCart += $itemcart['price'] * $itemcart['quantity'];
                    array_push($cart, $itemcart);
                    $added = true;
                }
            }
        }

        if (!in_array($request['params']['product']['id'], $productIds)) {
            $product = \App\Models\Product::whereId($request['params']['product']['id'])->with('vendor', 'offer','categories')->first();

            if($product->quantity>0 && $request['params']['quantity']>0 && $request['params']['quantity']<=$product->quantity)
            {
                $price = $product->price;

            $itemcart = array();

if(isset($request['params']['extraoptionvalue'])&&$request['params']['extraoptionvalue']!=''&&$request['params']['extraoptionvalue']!=0)
{
     $ProductSizes = ProductSizes::where('product_id', $product->id)->where('sizes_id',$request['params']['extraoptionvalue'])->first();
     $price = $ProductSizes->price;

}

            if ($product->offer) {

                if ($product->offer->is_fixed) {

                    $price = $price - $product->offer->fixed;
                } else {

                    $price = $price - (($price * $product->offer->percentage) / 100);
                }

            }
            $price = $price > 0 ? $price : 0 ;
            $optionvalues = array();


            $itemcart['product_id'] = $product->id;
            $itemcart['image'] = $product->main_image_path;
            $itemcart['product'] = $product;


            // $itemcart['options_value'] = $optionvalue;
            $itemcart['total'] = $price * $request['params']['quantity'];
            $itemcart['price'] = $price;
            $itemcart['quantity'] = $request['params']['quantity'];
                $itemcart['extraoption'] = '';
            if(isset($request['params']['extraoption'])) {
                $itemcart['extraoption'] = $request['params']['extraoption'];
            }
            array_push($cart, $itemcart);
            $countItems++;
            $totalCart += $price * $request['params']['quantity'];
                $added = true;
        }
    }

        $cartStorage->cart = json_encode($cart);
        //key exists, do stuff
        if ($create) {
            $cartStorage->save();
        } else {

            $cartStorage->update();
        }
        $cartStorage->refresh();
        $data = json_decode($cartStorage->cart, true);
        $out = [];

        foreach($data as $element) {
            $out[$element['product']['vendor_id']][] = ['item' =>  $element];
            $out[$element['product']['vendor_id']]['vendor'] = ['vendor' =>  $element['product']['vendor']];
        }
        $groupedcart = json_encode($out);
        $totalAfterGroup = number_format((float)$totalCart, 3, '.', '');

        session(['cartId' => $cartStorage->cart_id]);
        $enableOrder = true;
        $orderTotal = 0 ;
        $min_order = 0;
        foreach($data as $item)
        {
            $orderTotal+=$item['total'];
        }
        if($orderTotal < $min_order)
        {
            $enableOrder = false;
        }
         return json_encode(['cart' => $cartStorage->cart, 'cartId' => $cartStorage->cart_id, 'cartCount' => (($countItems>0)? $countItems : 0), 'totalCart' => number_format((float)($totalAfterGroup), 3, '.', ''),'groupedCart'=>$groupedcart,'added'=>$added,'enableOrder'=>$enableOrder]);
    }

    public static function update(Request $request)
    {
        Session::forget('couponCode');
        $countItems = 0;
        $totalCart = 0;
        $cartStorage = new CartStorage();
        if (session('cartId') && CartStorage::where('cart_id', session('cartId'))->first()) {
            $cartStorage = CartStorage::where('cart_id', session('cartId'))->first();
            $create = false;
        } else {
            $cartStorage = new CartStorage();
            $create = true;
        }

        if (Auth::User()) {
            $cartStorage->user_id = Auth::Id();
        } else {
            $cartStorage->user_id =  Str::random(10);
        }
        if (!session('cartId')) {
            $cartStorage->cart_id  = Str::random(10);
        } else {
            $cartStorage->cart_id = session('cartId');
        }



        $cart = array();
        $productIds = array();

        if ($cartStorage->cart) {

            foreach (json_decode($cartStorage->cart) as $item) {
                $productIds[] = $item->product_id;
            }

            foreach (json_decode($cartStorage->cart) as $item) {

                $itemcart = array();
                $optionvalues = array();
                $itemcart['product_id'] = $item->product_id;
                $itemcart['image'] = $item->image;
                $itemcart['product'] = $item->product;


                $itemcart['extraoption'] = $item->extraoption;

                $price = $item->price;

                if ($request['params']['product'][0]['product']['product_id'] == $item->product_id) {
                    $itemcart['total'] = $price * ($request['params']['product'][0]['quantity']);
                    $itemcart['price'] = $price;
                    $countItems++;


                    $itemcart['quantity'] = $request['params']['product'][0]['quantity'];
                    $itemcart['total'] = $price * $itemcart['quantity'];
                }
                else
                {
                    $itemcart['total'] = $price * $item->quantity;
                    $itemcart['price'] = $price;
                    $countItems++;


                    $itemcart['quantity'] = $item->quantity;
                    $itemcart['total'] = $price * $itemcart['quantity'];
                }

                    $totalCart += $itemcart['total'];


                array_push($cart, $itemcart);
            }
        }
        if (!in_array($request['params']['product'][0]['product']['product_id'], $productIds)) {
            $product = \App\Models\Product::whereId($request['params']['product'][0]['product']['product']['id'])->with('vendor','offer','categories')->first();
            $itemcart = array();
            $countItems++;
            $itemcart['product_id'] = $product->id;
            $itemcart['image'] = $product->main_image_path;
            $itemcart['product'] = $product;
            $itemcart['total'] = $product->price * $request['params']['product'][0]['quantity'];
            $itemcart['price'] = $product->price;
            $itemcart['quantity'] = $request['params']['product'][0]['quantity'];

            array_push($cart, $itemcart);
            $totalCart += $itemcart['total'];
        }

        $cartStorage->cart = json_encode($cart);
        //key exists, do stuff
        if ($create) {
            $cartStorage->save();
        } else {

            $cartStorage->update();
        }
        $cartStorage->refresh();
        $totalAfterGroup = 0;
        $data = json_decode($cartStorage->cart, true);
        $out = [];

        foreach($data as $element) {
            $out[$element['product']['vendor_id']][] = ['item' =>  $element];
            $out[$element['product']['vendor_id']]['vendor'] = ['vendor' =>  $element['product']['vendor']];
        }
        $groupedcart = json_encode($out);
        $enableOrder = true;
        $orderTotal = 0 ;
        $min_order = view()->shared('settings')->min_order;
        foreach($data as $item)
        {

            $orderTotal+=$item['total'];
        }
        if($orderTotal < $min_order)
        {
            $enableOrder = false;
        }

        $totalAfterGroup = number_format((float)$totalCart, 3, '.', '');
        session(['cartId' => $cartStorage->cart_id]);
        return json_encode(['cart' => $cartStorage->cart, 'cartId' => $cartStorage->cart_id, 'cartCount' => (($countItems>0)? $countItems : 0), 'totalCart' => $totalAfterGroup,'groupedCart'=>$groupedcart,'enableOrder'=>$enableOrder]);
    }

    public static function delete(Request $request)
    {
Session::forget('couponCode');
        $cartStorage = new CartStorage();
        if (session('cartId') && CartStorage::where('cart_id', session('cartId'))->first()) {
            $cartStorage = CartStorage::where('cart_id', session('cartId'))->first();
            $create = false;
        } else {
            $cartStorage = new CartStorage();
            $create = true;
        }

        if (Auth::User()) {
            $cartStorage->user_id = Auth::Id();
        } else {
            $cartStorage->user_id =  Str::random(10);
        }
        if (!session('cartId')) {
            $cartStorage->cart_id  = Str::random(10);
        } else {
            $cartStorage->cart_id = session('cartId');
        }

        $countItems = 0;
        $totalCart = 0;

        $cart = array();
        $productIds = array();

        if ($cartStorage->cart) {

            foreach (json_decode($cartStorage->cart) as $item) {

                $itemcart = array();
                if (sizeof($item) == 0) {
                    continue;
                }
                if ($request->product_id == $item->product_id) {
                    continue;
                }
                $itemcart['product_id'] = $item->product_id;
                $itemcart['image'] = $item->image;
                $itemcart['product'] = $item->product;

                $itemcart['total'] = $item->price * $item->quantity;
                $itemcart['price'] = $item->price;
                $itemcart['quantity'] = $item->quantity;

                $itemcart['total'] = $item->price * $item->quantity;
                $itemcart['price'] = $item->price;
                $itemcart['quantity'] = $item->quantity;

                $itemcart['extraoption'] = $item->extraoption;

                $countItems++;
                $totalCart += $item->price * $item->quantity;
                array_push($cart, $itemcart);
            }
            $cartStorage->cart = json_encode($cart);
        }
        $cartStorage->update();

        $cartStorage->refresh();
        $totalAfterGroup = 0;
        $itemsQuantity = array();
        foreach (json_decode($cartStorage->cart) as $item) {
            $itemsQuantity[$item->product_id] = $item->quantity;
        }

        foreach (json_decode($cartStorage->cart) as $item) {

            $totalAfterGroup += $itemsQuantity[$item->product_id] * $item->price;
        }
        $totalAfterGroup = number_format((float)$totalAfterGroup, 3, '.', '');
        session(['cartId' => $cartStorage->cart_id]);
        if (!$cartStorage->cart) {
            session(['cartId' => '']);
        }
        $data = json_decode($cartStorage->cart, true);
        $out = [];

        foreach($data as $element) {
            $out[$element['product']['vendor_id']][] = ['item' =>  $element];
            $out[$element['product']['vendor_id']]['vendor'] = ['vendor' =>  $element['product']['vendor']];
        }
        $groupedcart = json_encode($out);
        $enableOrder = true;
        $orderTotal = 0 ;
        $min_order = view()->shared('settings')->min_order;
        foreach($data as $item)
        {
            $orderTotal+=$item['total'];
        }
        if($orderTotal < $min_order)
        {
            $enableOrder = false;
        }
//dd($countItems);
//        $countItems--;
        return json_encode(['cart' => $cartStorage->cart, 'cartId' => $cartStorage->cart_id, 'cartCount' => (($countItems>0)? $countItems : 0),'cartbedforeTotal'=>number_format((float)($totalCart ), 3, '.', ''), 'totalCart' => $totalAfterGroup,'groupedCart'=>$groupedcart,'enableOrder'=>$enableOrder]);
    }
    public function getShippingMethodInfo(Request $request)
    {
        return ShippingMethods::find($request->id);
    }
    public function reorder(Request $request)
    {
        Session::forget('couponCode');
        $orderProducts = OrderProduct::where('order_id',$request->order_id)->get();
        $cartStorage = new CartStorage();
        if (session('cartId') && CartStorage::where('cart_id', session('cartId'))->first()) {
            $cartStorage = CartStorage::where('cart_id', session('cartId'))->first();
            $create = false;
        } else {
            $cartStorage = new CartStorage();
            $create = true;
        }
        if (Auth::User()) {
            $cartStorage->user_id = Auth::Id();
        } else {
            $cartStorage->user_id =  Str::random(10);
        }
        if (!session('cartId')) {
            $cartStorage->cart_id  = Str::random(10);
        } else {
            $cartStorage->cart_id = Str::random(10);
        }

        $countItems = 0;
        $totalCart = 0;

        $cart = array();
        $productIds = array();
        foreach ($orderProducts as $item) {
            $product = Product::Where('id',$item->product_id)->with('vendor','offer','categories')->first();

            if (!$item || $product->quantity<1) {
                continue;
            }


            $price = $product->price;
            if($product->offer)
            {

                if($product->offer->is_fixed)
                {

                    $price = $product->offer->is_fixed;
                }
                else
                {

                    $price = $product->price-(($product->price *  $product->offer->percentage)/100);
                }

            }


            $itemcart = array();

            if (isset($item->product_id)) {
                $itemcart['product_id'] = $item->product_id;
                $itemcart['image'] = $product->main_image_path;
                $itemcart['product'] = $product;
                $itemcart['total'] = $price * 1;
                $itemcart['price'] = $price;
                $itemcart['quantity'] = 1;
                $countItems++;

                $totalCart += $price  * 1;
                array_push($cart, $itemcart);
            }

        }

        $cartStorage->cart = json_encode($cart);
        //key exists, do stuff

        if ($create) {
             $cartStorage->save();
        } else {

            $cartStorage->update();
        }
        $cartStorage->refresh();
        $data = json_decode($cartStorage->cart, true);

        $out = [];

        foreach($data as $element) {
            $out[$element['product']['vendor_id']][] = ['item' =>  $element];
            $out[$element['product']['vendor_id']]['vendor'] = ['vendor' =>  $element['product']['vendor']];
        }
        $groupedcart = json_encode($out);
        $totalAfterGroup = number_format((float)$totalCart, 3, '.', '');

        session(['cartId' => $cartStorage->cart_id]);

        return json_encode(['cart' => $cartStorage->cart, 'cartId' => $cartStorage->cart_id, 'cartCount' => (($countItems>0)? $countItems : 0), 'totalCart' => $totalAfterGroup,'groupedCart'=>$groupedcart]);


    }
    public function checkDiscount(Request $request )
    {
        Session::forget('couponCode');
        $today = Carbon::today()->format('Y-m-d');
        $discount = Coupon::where('code',$request['params']['discount_code'])->where('status', 1)->where('from', '<=', $today)->where('to', '>=', $today)->first();
$cartStorage = CartStorage::where('cart_id', session('cartId'))->first();
$totalCart=0;
        foreach (json_decode($cartStorage->cart) as $item) {

                    $totalCart += $item->total;
                 }
         $cart =$totalCart;

         if ($discount && is_null($discount->num_uses) ) {

            if ($discount->is_fixed) {

                $grand_total =  $cart - ($discount->fixed  );
            } else {

                $amount = $cart * $discount->percentage / 100;
                $grand_total = $cart - $amount ;
            }
            session(['couponCode' => $request['params']['discount_code']]);
            return json_encode([
                'success' => true,
                'message' => 'coupun applied with ' . ($discount->is_fixed == 1 ? $discount->fixed . ' KD' : $discount->percentage . '%'),
                'total' =>number_format((float)( ($grand_total>0) ? $grand_total:0), 3, '.', ''),
                'totalbefore'=>number_format((float)($cart), 3, '.', ''),
                'value'=>number_format((float)( ($discount->is_fixed == 1) ? $discount->fixed   : $amount), 3, '.', ''),
            ]);
        }
        else if($discount&& !is_null($discount->num_uses)&&$discount->num_uses > 0)
        {
             if ($discount->is_fixed) {

                $grand_total =  $cart - ($discount->fixed  );
            } else {

                $amount = $cart * $discount->percentage / 100;
                $grand_total = $cart - $amount ;
            }
            session(['couponCode' => $request['params']['discount_code']]);
            return json_encode([
                'success' => true,
                'message' => 'coupun applied with ' . ($discount->is_fixed == 1 ? $discount->fixed . ' KD' : $discount->percentage . '%'),
                'total' =>number_format((float)( ($grand_total>0) ? $grand_total:0), 3, '.', ''),
                'totalbefore'=>number_format((float)($cart), 3, '.', ''),
                'value'=>number_format((float)( ($discount->is_fixed == 1) ? $discount->fixed   : $amount), 3, '.', ''),
            ]);
        }
        else
        {
            session(['couponCode' => '']);
            return json_encode([
                'success' => false,
                'message' => 'coupun not  applied ',
                'value'=>0,
                'total' => number_format((float)($cart), 3, '.', ''),
                'totalbefore'=>number_format((float)($cart), 3, '.', ''),
            ]);
        }

    }
}
