<?php

namespace App\Http\Controllers\Customer;

use App\Models\Area;
use App\Models\Countries;
use App\Models\Governorate;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderTrack;
use App\Models\OrderTransactions;
use App\Models\Product;
use App\Models\Settings;
use App\Models\UserAddress;
use App\Models\VendorOrderDeliveryCharges;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Redirect;
use Illuminate\Support\Facades\Validator;
use Mail;
use App\Models\Wishlist;
class CustomerController extends Controller
{
    public function profile(Request $request)
    {
        $user =Auth::user();
        $userAddress =Auth::user()->userAddress()->where('is_default',1)->with('countries')->get();
        $orders = Order::where('user_id' , Auth::Id())->with('useraddress.area')->orderBy('id', 'DESC')->limit(3)->get();

        return view('customer.profile')->with(compact('user','userAddress' ,'orders' ));
    }

    public function accountInfo(Request $request)
    {
        $user =Auth::user();

        return view('customer.accountinfo')->with('user',$user);
    }
    public function updateProfile(Request $request)
    {
        $user =Auth::user();
        if($request->email != $user->email) {
            $validate = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',

            ]);
        }
        else
        {
            $validate = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255'

            ]);
        }

        if($request->password)
        {
            $validate = Validator::make($request->all(), [
                'old_password' => 'required|string|min:6',
                'password' => 'required|string|min:6|confirmed',

            ]);


            if(!Hash::check($request->old_password, $user->password)){
                return back()->withInput($request->input())
                    ->withErrors(['old_password'=>'The specified password does not match the old password']);
            }
            else
            {
                $user->password = Hash::make($request->password);
            }


        }
        if($validate->fails())
        {
            return redirect()->back()
                ->withInput($request->input())
                ->withErrors($validate->errors());

        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->name =  $request->first_name.' '. $request->last_name;
        if($request->email != $user->email) {
            $user->email = $request->email;
        }
        $user->save();
        $request->session()->flash( 'success', "Your Account Has Been Updated" );
        $request->session()->flash( 'title', "Congratulations!" );
        return view('customer.accountinfo')->with('user',$user);
    }

    public function addressBook(Request $request)
    {
        $record = new UserAddress();
        $editingAddress = array_combine( $record->getFillable(), array_fill( 0, count( $record->getFillable() ), '' ) );
        $countries =Countries::all();
        $governorates = new Governorate;
        $governorates =  (object)array_combine( $governorates->getFillable(), array_fill( 0, count( $governorates->getFillable() ), '' ) );
        $areas =new Area();
        $areas = (object)array_combine( $areas->getFillable(), array_fill( 0, count( $areas->getFillable() ), '' ) );
        if(isset($request->id))
        {

            $editingAddress =  UserAddress::whereId($request->id)->first();;
            $governorates = Governorate::where('country_id',$editingAddress->country_id)->get();
            $areas = Area::where('governorate_id',$editingAddress->governorate_id)->get();
        }

        $user = Auth::user();
        $userAddress =Auth::user()->userAddress()->where('is_guest',0)->with('countries')->get();

        $create = true;
        return view('customer.address')->with(compact('user','userAddress' , 'countries' ,'areas', 'governorates' , 'create' , 'editingAddress'));
    }

    public function saveAddress(Request $request)
    {

        $type = 0 ;
        $is_default = 0 ;
        if($request->is_default == 2 )
        {
            $type = UserAddress::SHIPPINGADDRESSTYPE;
            UserAddress::where('user_id',  Auth::Id())->where('is_default', 1)->where('address_type', $type)->update(['is_default' => 0]);
            $is_default = 1 ;
        }
        else if($request->is_default == 1 )
        {
            $type = UserAddress::BILLINGADDRESSTYPE;
            UserAddress::where('user_id',  Auth::Id())->where('is_default', 1)->where('address_type', $type)->update(['is_default' => 0]);
            $is_default = 1 ;
        }

        $userAddress = UserAddress::updateOrCreate(['user_id'=>Auth::Id(),'id'=>$request->address_id],
            ['first_name'=>$request->first_name
                ,'second_name'=>$request->second_name,
                'phone_no'=>$request->phone_no,
                'fax'=>$request->fax,
                'city'=>$request->city,
                'company'=>$request->company,
                'zip_code'=>$request->zip_code,
                'country_id'=>$request->country_id,
                'governate_id'=>$request->governate_id,
                'area_id'=>$request->area_id,
                'city'=>$request->city,
                'block'=>$request->block,
                'street'=>$request->street,
                'avenue'=>$request->avenue,
                'floor'=>$request->floor,
                'flat'=>$request->flat,
                'extra_direction'=>$request->extra_direction,
                'address_type'=>$type,
                'is_default'=>$is_default,
                'user_id'=>Auth::Id(),

            ]);
        $request->session()->flash( 'success', "Your Address Has Been Saved" );
        $request->session()->flash( 'title', "Congratulations!" );
        return redirect(route('customer.address-book'));
    }
    public function getAddressInfo(Request $request)
    {
        $userAddress = UserAddress::find($request->id);
        $governorates = Governorate::where('country_id',$userAddress->country_id)->get();

        $areas = Area::where('governorate_id',$userAddress->governate_id)->get();

        return ['address'=>$userAddress,'governorates'=>$governorates,'areas'=>$areas];
    }
    public function deleteAddressInfo(Request $request)
    {
        $userAddress = UserAddress::whereId($request->id)->delete();


        return ['status'=>true ];
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect(route('home'));
    }

    public function addToWishList(Request $request)
    {

        $this->validate($request, [
            'product_id' => 'required',
        ]);

        if (!Auth::check()) {
            return response()->json(null, 401);
        }

        Wishlist::firstOrCreate([
            'user_id' => Auth::user()->id,
            'product_id' => $request->product_id
        ]);
        return response()->json(null, 200);
    }
    public function wishlist(Request $request)
    {


        if(!Auth::user())
        {
            return redirect(route('website.login'));
        }
        $productIds = Wishlist::where('user_id', Auth::Id())->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->with(['vendor','offer'])->paginate(15);
        return view('customer.wishlist')->with(compact('products'));
    }
    public function removeFromWishList(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required',
        ]);

        if (!Auth::check()) {
            return response()->json(null, 401);
        }

        Wishlist::where([
            'user_id' => Auth::user()->id,
            'product_id' => $request->product_id
        ])->delete();
        return response()->json(null, 200);
    }
    public function myorders(Request $request)
    {

        $orders = Order::where('user_id', Auth::Id())->where('status_id','!=', 2)->with('status', 'ordertrack.orderstatus','ordertransactions')->OrderBy('id', 'DESC')->paginate(15);

        return view('customer.Orders.my_orders')->with(compact('orders'));
    }

    public function viewOrderTransaction(Request $request)
    {
        $settings = Settings::find(1);
        $order = Order::where('unique_id', $request->unique_id)->first();
        $orderTransaction = OrderTransactions::where('order_id',$order->id)->first();
        return view('knet.success')->with(compact( 'settings' , 'orderTransaction' ,'order'));
    }

    public function trackorders(Request $request)
    {

        $orders = Order::with('status', 'ordertrack.orderstatus' , 'orderProducts' , 'orderProducts.vendor' )
            ->where('user_id', Auth::Id())
            ->where('status_id','!=', 2)

            ->groupBy()
            ->get();


        return view('customer.Orders.trackorders')->with(compact('orders'));
}

//        return view('customer.Orders.trackorders')->with(compact('orders'));



    public function orderhistory(Request $request)
    {
        $orders = Order::where('user_id', Auth::Id())->with('status', 'ordertrack.orderstatus')->OrderBy('id', 'DESC')->paginate(15);

        return view('customer.Orders.order_history')->with(compact('orders'));
    }

    public function viewOrder(Request $request)
    {
        $order = Order::where('unique_id', $request->unique_id)->first();

        return view('customer.Orders.order')->with(compact('order'));
    }

     public function viewInvoice(Request $request)
    {
        $order = Order::where('unique_id', $request->unique_id)->first();

        return view('customer.Orders.invoice')->with(compact('order'));
    }

    public function printInvoice(Request $request)

    {

		$order = Order::where( 'id', $request->id )->with( [
			'orderproducts' ,
			'userAddress'
		] )->firstOrFail();

		$setting = Settings::first();

		$vendorOrderDetails = VendorOrderDeliveryCharges::where('order_id' , $order->id)
		->first();

		return view( 'customer.Orders.customerinvoice_print' )->with( [

			'order'   => $order,
			'setting' => $setting,
			'vendorOrderDetails' => $vendorOrderDetails
		] );
    }

    public function printVendorInvoice(Request $request)

    {

		$order = Order::where( 'id', $request->id )->with( [
			'orderproducts' ,
			'userAddress'
		] )->firstOrFail();

		$setting = Settings::first();

		$vendorOrderDetails = VendorOrderDeliveryCharges::where('order_id' , $order->id)
		->first();

		return view( 'customer.Orders.vendorinvoice_print' )->with( [

			'order'   => $order,
			'setting' => $setting,
			'vendorOrderDetails' => $vendorOrderDetails
		] );
    }
}
