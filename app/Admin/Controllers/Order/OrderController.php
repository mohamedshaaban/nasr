<?php

namespace App\Admin\Controllers\Order;

use App\Http\Classes\Vend;
use App\Models\Area;
use App\Models\Governorate;
use App\Models\Order;
use App\Models\VendorCommissions;
use App\Models\VendorOrderDeliveryCharges;
use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\OrderProductOption;
use App\Models\OrderStatus;
use App\Models\PaymentMethods;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\Settings;
use App\Models\ShippingMethods;
use App\Models\OrderTrack;
use App\Models\UserAddress;
use App\Models\VendorAreaDeliveryCharges;
use App\Models\Vendors;
use App\User;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {

        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Order Detail')
            ->description('description')
            ->body(view('admin.order.details')
	            ->with('statusHis', OrderTrack::with('orderstatus','vendor')->where('order_id', $id)->orderBy('id', 'asc')->get())
	            ->with('order', Order::with('orderstatus','guestusers')->find($id))
            );
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        $orderstatus = OrderStatus::all();
        if(\Encore\Admin\Facades\Admin::user()->isRole('delivery'))
        {
            $orderstatus = OrderStatus::where('role_id',2)->get();
        }
        return $content
            ->header('Edit')
            ->description('description')
            ->body(view('admin.order.add_edit')
	            ->with('order', Order::getWithRelations($id))
	            ->with('order_status', $orderstatus)
	            ->with('statusHis', OrderTrack::with('orderstatus')->where('order_id', $id)->orderBy('id', 'desc')->get())
	            ->with('governorates', Governorate::where('status', 1)->get())
 	            ->with('payment_methods', PaymentMethods::all())
                ->with('areas', Area::where('status', 1)->get())
                ->with('shipping_methods', [])
                ->with('vendors',Vendors::where('parent_id',0)->get())

            );
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
         protected function form()
    {
        $form = new Form(new Order);


        return $form;
    }
    public function create(Content $content)
    {
        $orderstatus = OrderStatus::all();
        if(\Encore\Admin\Facades\Admin::user()->isRole('delivery'))
        {
            $orderstatus = OrderStatus::where('role_id',2)->get();
        }
        return $content
            ->header('Create New Order')
            ->description('description')
	        ->body(
	        	view('admin.order.add_edit')
			        ->with('order', new Order())
                    ->with('vendors',Vendors::where('parent_id',0)->get())
			        ->with('order_status', $orderstatus)
			        ->with('statusHis', [])
			        ->with('governorates', Governorate::where('status', 1)->get())
			        ->with('areas', Area::where('status', 1)->get())
			        ->with('shipping_methods', [])
			        ->with('payment_methods', PaymentMethods::all())
	        );
    }

	public function store(Request $request) {
		return $this->saveOrder(null, $request);
	}

	public function update($id, Request $request) {
    	Order::findOrFail($id);

		return $this->saveOrder($id, $request);
	}

	public function saveOrder( $order_id, Request $request ) {
        $order = Order::findOrFail($order_id);
        foreach($order->orderproducts as $product)
        {
            $this->updateQty($request,$product->product->sku , $product->product->code , $product->product->quantity - $product->quantity );
        }
	     $vendorTotalOrder = Array();
		if(count($request->get('product', []))==0) {
			return ['status' => false, 'msg'=> 'You have to add products!'];
		}

		if($request->get('customer-add-type','off')=='on'){
			if(User::where('email', $request->get('user-email'))->exists()){
				return ['status' => false, 'msg'=> 'Email has exist!'];
			}

			$user = User::create([
				'name' => $request->get('user-name'),
				'email' => $request->get('user-email'),
				'password' =>  bcrypt(str_random(8)),
				'is_active' => 1,
				'phone' =>  $request->get('user-phone'),
			]);

			$userID = $user->id;
		}
		else {
			$userID = $request->get('customer_id');
		}

		if($request->get('add-address-type', 'off')=='off' || $request->get('customer-add-type', 'off')=='on'){

			$userAddress = UserAddress::create([
				'address_type' => 2,
				'first_name' => $request->get('first_name'),
				'second_name' => $request->get('second_name'),
				'user_id' => $userID,
				'phone_no' => $request->get('phone_no'),
				'fax' => $request->get('fax'),
				'governorate_id' => $request->get('governorate_id'),
				'city' => $request->get('city'),
				'company' => $request->get('company'),
				'zip_code' => $request->get('zip_code'),
				'block' => $request->get('block'),
				'street' => $request->get('street'),
				'avenue' => $request->get('avenue'),
				'floor' => $request->get('floor'),
				'flat' => $request->get('flat'),
				'extra_direction' => $request->get('extra_direction'),
				'is_guest' => 0,
			]);

			$userAddressID = $userAddress->id;
		}
		else {

			$userAddressID = $request->get('address_id');
		}

		$address = UserAddress::find($userAddressID);


		$order = Order::findOrNew($order_id);

		$order->user_id =$order->user_id ? $order->user_id  : $userID;
		$order->address_id = $order->address_id ? $order->address_id : $address->id;
		$order->unique_id = isset($order->unique_id) ? $order->unique_id :uniqid();
// 		$order->order_date = date('Y-m-d H:i:s');
		$order->sub_total = isset($order->sub_total) ? $order->sub_total : 0;
		$order->total = isset($order->total) ? $order->total : 0;
		$order->is_paid = $order->is_paid ? $order->is_paid : $request->get('is_paid', 0);
		$order->status_id = $request->get('order_status');
		$order->payment_method = $order->payment_method ? $order->payment_method : $request->get('payment_method');

		$order->save();

		$total = 0;

		$vendProducts = [];
        $vendors = [];

		foreach ($request->get('product') as $product){
		    if(isset($product['price'])){
		    $item = Product::find($product['product_id']);
            array_push($vendors,$item->vendor_id );
			$oProduct = OrderProduct
				::updateOrCreate([
					'order_id' => $order->id,
					'product_id' => $product['product_id'],
				], [
					'quantity' => $product['quantity'],
					'sub_total' => $product['price'],
					'total' =>  $product['price']*$product['quantity'],
				]);





			$total +=$product['price']*$product['quantity'];
            if(!isset($vendorTotalOrder[$item->vendor_id]))
            {

                $vendorTotalOrder[$item->vendor_id]  = $product['price']*$product['quantity'];
            }
            else
            {
                $vendorTotalOrder[$item->vendor_id]  = $vendorTotalOrder[$item->vendor_id]  +$product['price']*$product['quantity'];
            }


            $vendorDeliveryCharges = VendorAreaDeliveryCharges::where('vendor_id',$item->vendor->id)->pluck('delivery_charge' , 'area_id');

            if(array_key_exists($address->area_id,$vendorDeliveryCharges->toArray() ))
            {
                $vendorAreas[$item->vendor->id]= $vendorDeliveryCharges[$address->area_id];
            }
		}
		}


				// $order->delivery_charges = 0;



// 		$order->sub_total = $total;
// 		$order->total = $total+$order->delivery_charges;

		$order->save();

        // $vendorTotalOrder = Array();
        $vendorAreas = Array();
        $totalDelivdryCharge = 0;

         foreach ($vendorTotalOrder as $key =>$vendorTotal)
        {

                $orderTack  = OrderTrack::updateOrCreate(['order_id'=>$order->id , 'vendor_id'=>$key ,'order_status_id'=>$request->order_status],[
                'order_id'=>$order->id ,
                'vendor_id'=>$key,
                'order_status_id'=>$request->order_status,

            ]);

            $vendorCommission = VendorCommissions::where('vendor_id',$key)->first();
            $commission_kd =  $vendorCommission->fixed ;
            $commission_precentage = $vendorCommission->fixed;
            if($order->payment_method == 1 && isset($vendorAreas[$key] ) && isset($vendorTotalOrder[$key]) )
            {
                $commission_kd = ($vendorAreas[$key] + $vendorTotalOrder[$key]) * ( $vendorCommission->precentage / 100 );
                $commission_precentage = $vendorCommission->precentage;
            }
            if(isset($vendorAreas[$key]))
{
            VendorOrderDeliveryCharges::updateOrCreate(['order_id'=>$order->id , 'vendor_id'=>$key ],[
                'order_id'=>$order->id ,
                'vendor_id'=>$key,
                'delivery_charges'=>$vendorAreas[$key],
                'total'=>$vendorTotalOrder[$key],
                'area_id'=>$address->area_id,
                'commission_kd'=>$commission_kd,
                'commission_percentage'=>$commission_precentage,
                'transferred'=>0
            ]);
}
        }
        $order->total = $order->total + array_sum($vendorAreas);
        if(isset($request->order_status))
        {
            // foreach($vendors as  $vendid) {
            //     $orderTracking = OrderTrack::Create([
            //         'order_id' => $order->id,
            //         'vendor_id' => $vendid,
            //         'order_status_id' => $request->order_status,


            //     ]);
            // }
            $tracks =  OrderTrack::where('order_id',$order->id)->whereRaw('id IN (select MAX(id) FROM order_track GROUP BY vendor_id)')->get();

            $orderstatus=[];
            foreach($tracks as $track)
            {
                $orderstatus[$track->vendor_id]=$track->order_status_id;

            }

            if (count(array_unique($orderstatus)) === 1 && end($orderstatus) === 2) {

                Order::where('id',$order->id)->update(['status_id'=>2]);

            }
            else
            {
                Order::where('id',$order->id)->update(['status_id'=>4]);
            }

            $order->sub_total = $total;
        }
		return ['status' => true];
	}

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);
        $grid->model()->orderBy('id', 'desc');
        $grid->id('Id');
        $grid->customername(' Customer');
        $grid->unique_id('Order');




 	    $grid->is_paid('Is Confirmed')->display(function($field){
	    	if($field){
	    		return '<span class="badge badge-success">Confirmed</span>';
		    }
		    return '<span class="badge badge-danger">Not Confirmed</span>';
	    });
	    $grid->paymentmethods()->title_en('Payment method');

	    $grid->order_date('Order date');

	    $grid->actions(function ($actions) {
	        $actions->disableDelete();
	        $actions->disableView();
     $actions->append(
        '<a href="'.$actions->getResource().'/'.$actions->getKey().'" target="_blank" class="grid-row-edit" style="margin-right:4px">
        <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"></i></a>' );

});

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('product_id', 'Product')->select(Product::all()->pluck('name_en', 'id'));
            $filter->between('order_date', 'From')->datetime();
            $filter->equal('unique_id','Order ID');
            $filter->equal('payment_method')->select(PaymentMethods::pluck('title_en', 'id'));
        });

        return $grid;
    }

	public function customer_ajax( Request $request ) {
		$q = $request->get( 'q' );

		return User
			::where( 'is_active', 1)
			->where( function ( $query ) use ( $q ) {
				$query->where( 'name', 'like', "%$q%" );
				$query->orWhere( 'email', 'like', "%$q%" );
			} )
			->with('userAddress')
			->paginate();
	}
    public function vendor_ajax( Request $request ) {
        $q = $request->get( 'q' );

        return Vendors
            ::where( 'is_active', 1)
            ->where('parent_id',0)
            ->where( function ( $query ) use ( $q ) {
                $query->where( 'name', 'like', "%$q%" );
                $query->orWhere( 'email', 'like', "%$q%" );
            } )

            ->with('product')
            ->paginate();
    }
	public function product_ajax( Request $request ) {
		$q = $request->get( 'vendor_id' );

		return Product
			::where( 'status', 1)
			->where('vendor_id',$q)

			->get();
	}
    public function product_price( Request $request ) {
        $product = \App\Models\Product::whereId($request->product_id)->with('vendor', 'offer')->first();

            $itemcart = array();
            $price = $product->price;

            if ($product->offer) {

                if ($product->offer->is_fixed) {

                    $price = $product->offer->fixed;
                } else {

                    $price = $product->price - (($product->price * $product->offer->percentage) / 100);
                }

            }


        return json_encode(['price'=>$price,'product'=>$product]);
    }

    public function createToke()
    {
        $ch = curl_init();
        $fields = [
            'email'=>'info@masdr.me',
            'password'=>'Masdr2030'
        ];
        $data_string = json_encode($fields);
        curl_setopt($ch, CURLOPT_URL,"https://app.skuvault.com/api/gettokens");
//        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

// Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = (curl_exec($ch));
        $server_output_arr = json_decode($server_output, true);
        $TenantToken =$server_output_arr['TenantToken'];
        $UserToken =$server_output_arr['UserToken'];



        //////////////////

        $ch = curl_init();
        $fields_ware = [
            'TenantToken' => $TenantToken,
            'UserToken' => $UserToken
        ];
        $data_string_ware = json_encode($fields_ware);
        curl_setopt($ch, CURLOPT_URL, "https://app.skuvault.com/api/inventory/getLocations");
//        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string_ware);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output_ware = (curl_exec($ch));
        $server_output_ware_arr = json_decode($server_output_ware, true);
        $server_output_ware_arr = $server_output_ware_arr;
        ///
        ///
        return ['TenantToken'=>$TenantToken,'UserToken'=>$UserToken,'locationCode'=>$server_output_ware_arr['Items'][0]['LocationCode']];
        curl_close ($ch);
    }
    public function updateQty(Request $request , $sku,$code,$qty)
    {
        $TenantToken = $this->createToke()['TenantToken'];
        $UserToken = $this->createToke()['UserToken'];
        $locationCode = $this->createToke()['locationCode'];

        //////////////////

        $ch = curl_init();
        $fields_ware = [
            "PageNumber"=>0,
            'TenantToken' => $TenantToken,
            'UserToken' => $UserToken
        ];
        $data_string_ware = json_encode($fields_ware);
        curl_setopt($ch, CURLOPT_URL, "https://app.skuvault.com/api/inventory/getWarehouses");
//        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string_ware);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output_ware = (curl_exec($ch));
        $server_output_ware_arr = json_decode($server_output_ware, true);
        $server_output_ware_arr = $server_output_ware_arr['Warehouses'][0];
        ///


        $ch = curl_init();
        $fields = [
            "Sku"=>$sku,
            "Code"=>$code,
            "WarehouseId"=>$server_output_ware_arr['Id'],
            "LocationCode"=>"01",
            "Quantity"=>$qty,
            'TenantToken' => $TenantToken,
            'UserToken' => $UserToken
        ];
        $data_string = json_encode($fields);
        curl_setopt($ch, CURLOPT_URL, "https://app.skuvault.com/api/inventory/setItemQuantity");
//        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

// Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = (curl_exec($ch));
        $server_output_arr = json_decode($server_output, true);
        return ($server_output_arr);
    }


}
