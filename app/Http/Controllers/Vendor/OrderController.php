<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Order;
use App\Models\orderproducts;
use App\Models\OrderStatus;
use App\Models\OrderTrack;
use App\Models\Vendors;
use App\Models\Product;
use App\Models\VendorOrderDeliveryCharges;
use App\User;
use App\Models\UserAddress;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Models\VendorFullDay;
use App\Models\Settings;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItemOptions;
use App\Mail\OrderMail;
use Mail;
use App\Models\OrderProduct;
use Auth;


class OrderController extends Controller {

	/**
	 * check if vendor has current product and return auth user
	 *
	 * @param Integer $orderID
	 *
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $permission = Auth::guard('vendor')->user()->permission;
            if( $permission == Vendors::VENDOR_FULL_ACCESS || $permission == Vendors::VENDOR_SALES_ACCESS)
            {

                return $next($request);

            }
            return redirect('/vendor/404');
        });

    }
	protected function checkPermission( $orderID ) {
		$auth = \Auth::guard( 'vendor' )->user();
        $vendor_id = $auth->id ;

        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }
		$orderproducts = OrderProduct::where( 'vendor_id', $vendor_id )
		                        ->where( 'order_id', $orderID )
		                        ->get();

		if ( count( $orderproducts ) < 1 ) {
			abort( 404 );
		}

		return $auth;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index( Request $request ) {
		$auth = \Auth::guard( $this->vendor_guard)->user();

		$orders = $this->filter( $request, $auth );
        $orderStatus = OrderStatus::all();

	   
		$data = [
			'orders'        => $orders,
            'request'       => $request,
            'orderStatus' =>$orderStatus
		];

//		if($this->return_json){
//			return response()->json([
//				'status' => $this->successStatus,
//                'success' => true,
//				'data' => $data
//			],200);
//		}
		return view( 'vendors.order.index' )
			->with( $data);
	}

	protected function filter( Request $request, $auth ) {
        $auth = \Auth::guard('vendor')->user();
        $vendor_id = $auth->id ;

        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }
		$orders = Order::with( [ 'UserAddress', 'user', 'UserAddress.area', 'orderproducts' ,'guestusers' ,'ordertrack.orderstatus'] )
		               ->whereHas( 'orderproducts', function ( $q ) use ( $vendor_id ) {
			               $q->where( 'vendor_id', $vendor_id );
		               } )->with( [
				'orderproducts' => function ( $query ) use ( $vendor_id ) {
					$query->where( 'vendor_id', $vendor_id );
				}
			] );

		if ( $request->has( 'order_date' ) && $request->get( 'order_date' )!='' ) {
			$orders->whereDate( 'order_date', '=', $request->get( 'order_date' ) );
		}
		if ( $request->has( 'trackstatus' ) ) {
		    $tracks = OrderTrack::select(DB::raw('t.* , max(created_at) as created_at '))
                ->from(DB::raw('(SELECT * FROM order_track  ORDER BY created_at DESC) t'))
                ->where('order_status_id' , $request->get( 'trackstatus' ))
                ->groupBy('t.order_id')
                ->pluck('order_id');

			$orders->whereIn( 'id',  $tracks->toArray() ) ;
		}

        // $orders->where('is_paid',1);
		$orders = $orders->orderBy( 'order_date', 'desc' )

            ->paginate(10);



		return $orders;
	}

	public function setFullDay( Request $request, $date ) {
		$auth = \Auth::guard( 'vendor' )->user();

		$fullDay = VendorFullDay::where( 'vendor_id', $auth->id )
		                        ->where( 'date', 'like', $date . '%' )
		                        ->first();

		if ( $request->get( 'status' ) == 'add' ) {
			if ( ! $fullDay ) {
				VendorFullDay::create( [
					'date'      => $date,
					'vendor_id' => $auth->id
				] );
			}
		} else {
			$fullDay->delete();
		}

		return response()->json( [
			'status' => true,
		], 200 );

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		return $this->offlineOrderView( new Order() , 'Create' );
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Order $order
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( Order $order ) {
		$auth        = $this->checkPermission( $order->id );

		$commissions = $auth->vendorcommissions()->get();

		$setting     = Settings::first();
        $vendor_id = $auth->id ;
        $orderStatus = OrderStatus::where('role_id',3)->get();
        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }
		$vendorOrderDetails = VendorOrderDeliveryCharges::with('vendors')->where('order_id' , $order->id)
		->where('vendor_id' , $vendor_id)
		->first();

		$order = Order::where( 'id', $order->id )->with( ['ordertrack',
			'orderproducts' => function ( $query ) use ( $vendor_id ) {
				$query->where( 'vendor_id',$vendor_id );
			},
			'userAddress'
		] )->firstOrFail();

		return view( 'vendors.order.show' )->with( [
			'order'       => $order,
			'commissions' => $commissions,
			'setting'     => $setting,
			'vendorOrderDetails' => $vendorOrderDetails,
            'vendor_id' =>$vendor_id,
            'orderStatus' =>$orderStatus
		] );
	}


	/**
	 * Save Offline Order
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Validation\ValidationException
	 */

	public function productDetailOrderAjax( $id, $order_id ) {

		if ( $order_id != 0 ) {
			$order   = Order::find( $order_id );
			$product = getProductFromOrder( $order->unique_id, $id );
		} else {
			$product = false;
		}

		if ( ! $product ) {
			$product = Product::findOrFail( $id );
		}

		return [
			'success' => true,
			'html'    => view( 'products.popup' )
				->with( 'product', $product )
				->render()
		];
	}

	public function setOrdersIsAcknowledgedByDate( $date ) {
		$auth = \Auth::guard( 'vendor' )->user();

		$orders = Order::whereHas( 'orderproducts', function ( $q ) use ( $auth ) {
			$q->where( 'vendor_id', $auth->id );
		} )->whereDate( 'order_date', '=', $date )
		               ->where( 'order_type', Order::NEW_ORDER )->get();

		foreach ( $orders as $order ) {
			$order->orderproducts()->update( [
				'is_acknowledged' => 1
			] );
		}

		Session::flash( 'success', 'Acknowledged Updated for date ' . $date );

		return redirect()->back();
	}

	public function printInvoice($unique_id ) {
		$auth = \Auth::guard( 'vendor' )->user();
        $vendor_id = $auth->id ;

        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }
		$order = Order::where( 'unique_id', $unique_id )->with( [
			'orderproducts' => function ( $query ) use ( $vendor_id ) {
				$query->where( 'vendor_id', $vendor_id );
			},
			'userAddress'
		] )->firstOrFail();

		$setting = Settings::first();

		$this->checkPermission( $order->id );
		$vendorOrderDetails = VendorOrderDeliveryCharges::where('order_id' , $order->id)
		->where('vendor_id' , $vendor_id)->first();

		return view( 'vendors.order.invoice_print' )->with( [
			'order'   => $order,
			'setting' => $setting,
			'vendorOrderDetails' => $vendorOrderDetails
		] );
	}


	protected function validation( $data ) {
		$data['email'] = $data['customer_email'];

		return Validator::make( $data, [
			'order_date'      => 'required|string|max:190',
			'order_time'      => 'required|string|max:190',
			'email'           => 'required|string|email|max:190',
			'customer_name'   => 'required|string',
			'customer_mobile' => 'required|numeric',
			'address_area'    => 'required|string',
			'address_block'   => 'required|string',
			'address_street'  => 'required|string',
			// 'address_avenue' => 'required|string',
			'address_house'   => 'required|string',
			// 'address_floor' => 'required|string',
			// 'address_loaction' => 'required|string',
			'payment_status'  => 'required|numeric'
		] );
	}

	protected function createUser( $data ) {
		$user = User::firstOrCreate( [ 'email' => $data['customer_email'] ], [
			'name'     => $data['customer_name'],
			'email'    => $data['customer_email'],
			'password' => bcrypt( str_random( 8 ) ),
			'key'      => str_random( 8 ),
		] );

		return $user;
	}

	protected function createUserAddress( $data, $user ) {

		$address = UserAddress::updateOrCreate( [ 'user_id' => $user->id ], [
			'profile_name'   => 'offline order',
			'area_id'        => $data['address_area'],
			'bldg_no'        => $data['address_house'],
			'block'          => $data['address_block'],
			'street'         => $data['address_street'],
			'avenue'         => $data['address_avenue'],
			'floor'          => $data['address_floor'],
			'flat_no'        => $data['address_apartment'],
			'exra_direction' => $data['address_loaction'],
			'mobile_no'      => $data['customer_mobile']
		] );

		return $address;
	}

	protected function createOrder( $data, $user, $address ) {

		return Order::updateOrCreate( [ 'id' => isset( $data['id'] ) ? $data['id'] : 0 ], [
			'user_id'                        => $user->id,
			'unique_id'                      => str_random( 6 ),
			'order_type'                     => Order::OFFLINE_ORDER,
			'order_date'                     => date( 'Y-m-d', strtotime( $data['order_date'] ) ) . ' ' . date( "H:i:m", strtotime( $data['order_time'] ) ),
			'sub_total'                      => 0,
			'total'                          => 0,
			'delivery_charges'               => 0,
			'address_id'                     => $address->id,
			'is_paid'                        => $data['payment_status'] == Order::PAYMENT_STATUS_YES ? Order::IS_PAID : Order::NOT_PAID,
			'payment_status'                 => $data['payment_status'],
			'is_confirmed'                   => 0,
			'amount_to_paid_after_confirmed' => 0,
			'amount_paid'                    => 0,
		] );
	}

	protected function addItemToOrder( $data, $user, $order, $vendorID ) {

		foreach ( $data['products'] as $productID => $row ) {
			if ( isset( $row['product_details'] ) ) {
				// Add or Update products
				$orderItem = orderproducts::updateOrCreate( [
					'order_id'   => $order->id,
					'product_id' => $productID
				], [
					'user_id'         => $user->id,
					'price'           => $row['product_details']['price'],
					'quantitiy'       => $row['product_details']['quantity'],
					'payment_type'    => 1,
					'is_paid'         => ( isset( $data['payment_status'] ) && $data['payment_status'] == Order::PAYMENT_STATUS_YES ) ? Order::IS_PAID : Order::NOT_PAID,
					'is_confirmed'    => 0,
					'vendor_id'       => $vendorID,
					'start_time'      => now(),
					'end_time'        => now(),
					'is_acknowledged' => 1
				] );


				// Add or Update Product Options
				$orderItemOptionIDs = [];
				foreach ( $row['product_details'] as $key => $value ) {
					if ( strpos( $key, 'option_' ) !== false ) {
						$option_id = str_replace( 'product_option_', '', $key );
						$option_id = str_replace( 'option_', '', $option_id );

						$orderItemOption = OrderItemOptions::updateOrCreate( [
							'option_id'     => $option_id,
							'order_item_id' => $orderItem->id,
						], [
							'quantity' => 1,
							'value'    => is_array( $value ) ? json_encode( $value ) : $value
						] );

						$orderItemOptionIDs[] = $orderItemOption->id;
					}
				}

				OrderItemOptions
					::where( 'order_item_id', $orderItem->id )
					->whereNotIn( 'id', $orderItemOptionIDs )
					->delete();
			}
		}

		OrderItemOptions::whereIn( 'order_item_id', function ( $q ) use ( $order, $data ) {
			$q->select( 'id' )
			  ->from( 'order_items' )
			  ->where( 'order_id', $order->id )
			  ->where( 'is_paid', 0 )
			  ->whereNotIn( 'product_id', array_keys( $data['products'] ) );
		} )->delete();


		orderproducts
			::where( 'order_id', $order->id )
			->whereNotIn( 'product_id', array_keys( $data['products'] ) )
			->where( 'is_paid', 0 )
			->delete();

		return orderproducts
			::where( 'order_id', $order->id )
			->sum( 'price' );
	}

 public function save(Request $request)
 {
     $auth = \Auth::guard( 'vendor' )->user();
     $vendor_id = $auth->id ;
        Order::where('id',$request->order_id)->update(['status_id'=>4]);
     if($auth->parent_id != 0)
     {
         $vendor_id = $auth->parent_id;
     }
     $orderTracking = OrderTrack::Create([
         'vendor_id' =>$vendor_id,
         'order_id' =>$request->order_id,
         'order_status_id' =>$request->trackstatus,


     ]);
$tracks =  OrderTrack::where('order_id',$request->order_id)->whereRaw('id IN (select MAX(id) FROM order_track GROUP BY vendor_id)')->get();
$orderstatus=[];
foreach($tracks as $track)
{
    $orderstatus[$track->vendor_id]=$track->order_status_id;

}

     if (count(array_unique($orderstatus)) === 1 && end($orderstatus) === 2) {

     Order::where('id',$request->order_id)->update(['status_id'=>2]);

     }
     else
     {
         Order::where('id',$request->order_id)->update(['status_id'=>4]);
     }


     return redirect()->back();
 }

}
