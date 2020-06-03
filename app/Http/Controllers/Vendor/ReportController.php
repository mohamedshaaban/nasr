<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Area;
use App\Models\Category;
use App\Models\Governorate;
use App\Models\OrderProduct;
use App\Models\OrderStatus;
use App\Models\PaymentMethods;
use App\Models\UserAddress;
use App\Models\VendorOrderDeliveryCharges;
use App\Models\Vendors;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\OrderItems;
use App\Models\OrderTrack;
use Illuminate\Support\Facades\DB;
use App\Models\ProductReviews;
use App\Models\Product;
use App\Models\CommissionChanges;
use App\Models\VendorOrdersDetails;
use Session;
use App\Models\Tag;use Auth;

class ReportController extends Controller
{
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
    public function index(Request $request, $type)
    {
        $auth = \Auth::guard('vendor')->user();

        switch ($type) {
            case 'sales':
                return $this->sales($request, $auth);
                break;
            case 'inventory':
                return $this->inventory($request, $auth);
                break;
            case 'delivery':
                return $this->deliveryArea($request, $auth);
                break;
            case 'orders':
                return $this->orders($request, $auth);
                break;
            case 'commission':
                return $this->commission($request, $auth);
                break;

            default:
                abort(404);
                break;
        }
    }
    protected function sales(Request $request, $auth)
    {
        $auth = \Auth::guard('vendor')->user();


        $totalPayment = $this->getTotalPayment($auth, $request);


        $orders = $this->getOrdersGroupByType($auth, $request);
        $salesGroupByDay = $this->getSalesGroupByDay($auth, $request);

        $paymentmethods = PaymentMethods::where('active',1)->get();
        return view('vendors.reports.sales')->with([
            'orders' => $orders,
            'totalPayment'=>$totalPayment,
            'salesGroupByDay'=>$salesGroupByDay,
            'request' => $request,
            'paymentmethods' =>$paymentmethods
        ]);
    }

    protected function inventory(Request $request, $auth)
    {
        $auth = \Auth::guard('vendor')->user();

        $categories = Category::all();
        $products = $this->getProductsReport($auth, $request);

        return view('vendors.reports.products')->with([
            'products' => $products,
            'categories' => $categories,
            'request' => $request,
        ]);
    }



    protected function deliveryArea(Request $request, $auth)
    {
        $products = $this->getProductsReport($auth , $request) ;
        $vendor_id = $auth->id ;

        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }
        $vendor = Vendors::find($vendor_id);

        $governates = Governorate::where('country_id', $vendor->country_id)->pluck('id')->toArray();
        $areas = Area::whereIn('governorate_id' , $governates)->get();

        $users = User::all();
        $orders = $this->getOrdersGroupByType($auth, $request);
        return view('vendors.reports.delivery')
            ->with([
                'products' => $products,
                'request' => $request,
                'areas'=>$areas,
                'users'=>$users,
                'orders' => $orders
            ]);
    }
    protected function orders(Request $request, $auth)
    {
        $auth = \Auth::guard('vendor')->user();


        $totalPayment = $this->getTotalPayment($auth, $request);


        $orders = $this->getOrdersGroupByType($auth, $request);

        $salesGroupByDay = $this->getSalesGroupByDay($auth, $request);
        $orderStatus = OrderStatus::all();
        $categories = Category::all();
        return view('vendors.reports.orders')->with([
            'orders' => $orders,
            'totalPayment'=>$totalPayment,
            'salesGroupByDay'=>$salesGroupByDay,
            'request' => $request,
            'orderStatus' =>$orderStatus,
            'categories' =>$categories
        ]);
    }
    protected function commission(Request $request, $auth)
    {

        $vendor_id = $auth->id ;

        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }

        $vendorcommission = VendorOrderDeliveryCharges::with('order')->where('vendor_id' , $vendor_id);
        $orderPaidIds = Order::where('is_paid' , 1)->pluck('id')->toArray();
             $vendorcommission->whereIn('order_id', $orderPaidIds);
        if ($request->has('from_date') && !is_null($request->from_date)) {
             $vendorcommission->where('created_at', '>=', $request->get('from_date'));
        }
        if ($request->has('to_date') && !is_null($request->to_date)) {
            $vendorcommission->where('created_at', '<=', $request->get('to_date'));

        }
        $vendorcommission = $vendorcommission->get();
        return view('vendors.reports.commission')
            ->with([
                'vendorcommission' => $vendorcommission,
                'request' => $request,
            ]);
    }


    private function getTotalPayment($auth, Request $request)
    {
         // total payment
        $totalPayment = VendorOrderDeliveryCharges::select(\DB::raw('`vendor_id`, sum(`total` + `delivery_charges`) as total_payment'))
            ->where('vendor_id', $auth->id)
            ->where('total', '>', 0);

        if ($request->has('from_date') && !is_null($request->from_date)) {
            $totalPayment = $totalPayment->where('created_at', '>=', $request->get('from_date'));
        }

        if ($request->has('to_date') && !is_null($request->to_date)) {
            $totalPayment = $totalPayment->where('created_at', '<=', $request->get('to_date'));

        }

        $totalPayment = $totalPayment
            ->groupBy('vendor_id')
            ->get()
            ->first();

        return $totalPayment;
    }

    private function getOrdersGroupByType($auth, Request $request)
    {
        $vendor_id = $auth->id ;

        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }
        $orders = OrderProduct::where('vendor_id', $vendor_id)
            ->where('total', '>', 0)->with(['order','order.ordertrack','order.user','order.useraddress','order.paymentmethods']);
         $orderPaidIds = Order::where('is_paid' , 1)->pluck('id')->toArray();
             $orders->whereIn('order_id', $orderPaidIds);
        if ($request->has('from_date') && !is_null($request->from_date)) {
             $orders->where('created_at', '>=', $request->get('from_date'));
        }
        if ($request->has('to_date') && !is_null($request->to_date)) {
             $orders->where('created_at', '<=', $request->get('to_date'));

        }
        if ($request->has('payment_method') && !is_null($request->payment_method)) {
            $orderIds = Order::where('payment_method' , $request->payment_method)->pluck('id')->toArray();
             $orders->whereIn('order_id', $orderIds);

        }
        if ($request->has('category') && !is_null($request->category)) {
            $products = Product::where('vendor_id', $vendor_id)
                ->with('orderproducts');
            $chkCategory = Category::whereIn('parent_id',is_array($request->category) ? $request->category : [$request->category])->pluck('id');

            if(sizeof($chkCategory)>0)
            {

                $request->category = $chkCategory;

                $products->whereHas('categories', function ($q) use ($request , $chkCategory) {
                    $q->whereIn('id', $chkCategory);
                });
            }
            else {

                $request->category = [ $request->category ];
                $products->whereHas('categories', function ($q) use ($request) {
                    $q->whereIn('id', is_array($request->category) ? $request->category : [$request->category]);
                });
            }
            $productIds = $products->pluck('id')->toArray();
            $orders->whereIn('product_id' , $productIds);
        }
        if ( $request->has( 'status' )  && !is_null($request->status)) {
            $tracks = OrderTrack::select(DB::raw('t.* , max(id) as id '))
                ->from(DB::raw('(SELECT * FROM order_track  ORDER BY id DESC) t'))
                ->where('order_status_id' , $request->get( 'status' ))
                ->groupBy('t.order_id')
                ->pluck('order_id');


            $orders->whereIn( 'order_id',  $tracks->toArray() ) ;
        }
        if ($request->has('area') && !is_null($request->area)) {

            $orderIds = Order::whereIn('address_id' ,UserAddress::where('area_id', $request->area)->pluck('id')->toArray())->pluck('id')->toArray();
            $orders->whereIn('order_id', $orderIds);

        }
        if ($request->has('user') && !is_null($request->user)) {
            $orderIds = Order::where('user_id' , $request->user)->pluck('id')->toArray();
             $orders->whereIn('order_id', $orderIds);

        }
        $orders = $orders->orderBy('id', 'DESC')->get();



        return $orders;
    }

    private function checkCommissionIfChanged(Request $request, $auth)
    {
        $commissionChange = CommissionChanges::where('vendor_id', $auth->id);

        $commissionChangeCount = 0;

        if ($request->has('from_date') && !is_null($request->from_date)) {
            $commissionChange = $commissionChange->whereDate('created_at', '>=', $request->get('from_date'));

            $commissionChangeCount = $commissionChange->count();
        }

        if ($request->has('to_date') && !is_null($request->to_date)) {
            $commissionChange = $commissionChange->whereDate('created_at', '<=', $request->get('to_date'));

            $commissionChangeCount = $commissionChange->count();
        }

        if ($commissionChangeCount > 1) {
            return false;
        }

        return true;

    }

    protected function getSalesGroupByDay($auth, Request $request)
    {
        $salesPerDay = [];
        $vendor_id = $auth->id ;

        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }

        $orders = OrderProduct::where('vendor_id', $vendor_id)
            ->where('total', '>', 0)->with('order');
        $orderPaidIds = Order::where('is_paid' , 1)->pluck('id')->toArray();
             $orders->whereIn('order_id', $orderPaidIds);
        if ($request->has('from_date') && !is_null($request->from_date)) {
            $orders = $orders->where('created_at', '>=', $request->get('from_date'));
        }
        if ($request->has('to_date') && !is_null($request->to_date)) {
            $orders = $orders->where('created_at', '<=', $request->get('to_date'));

        }
        if ($request->has('payment_method') && !is_null($request->payment_method)) {
            $orderIds = Order::where('payment_method' , $request->payment_method)->pluck('id')->toArray();
            $orders = $orders->whereIn('order_id', $orderIds);

        }
        $orders = $orders->orderBy('id', 'DESC')->pluck('order_id')->toArray();

        $salesByTypeAndDate = VendorOrderDeliveryCharges::whereIn('order_id',$orders)->where('vendor_id', $vendor_id)
            ->where('total', '>', 0);


        $dayInMonth = \Carbon\Carbon::parse(date('Y-m-d'))->daysInMonth;
        $date = date('Y-m-');
       
        // check filter
        if ($request->has('from_date') && !is_null($request->from_date)) {
            $salesByTypeAndDate = $salesByTypeAndDate->whereYear('created_at', date('Y', strtotime($request->from_date)))
                ->whereMonth('created_at', date('m', strtotime($request->from_date)));

            $date = date('Y-m-', strtotime($request->from_date));
            $dayInMonth = \Carbon\Carbon::parse(date('Y-m-d', strtotime($request->from_date)))->daysInMonth;
        }

        $salesByTypeAndDate = $salesByTypeAndDate
            ->get()
            ->groupBy([function ($q) {
                return Carbon::parse($q->created_at)->format('Y-m-d');
            }]);

        $ordersSalesGroupByDate = [];
        foreach ($salesByTypeAndDate as $orderDate => $ordersByType) {

            foreach ($ordersByType as $orderType => $orders) {

                $ordersSalesGroupByDate[$orderDate][$orderType] = $orders->total;
            }
        }


        for ($day = 1; $day <= $dayInMonth; $day++) {
            $d = strlen($day) == 1 ? $date . '0' . $day : $date . $day;

            if (isset($ordersSalesGroupByDate[$d])) {
                isset($ordersSalesGroupByDate[$d]  ) ? $salesPerDay[$d]  = $ordersSalesGroupByDate[$d]  : $salesPerDay[$d]  = 0;
             }
            else {
                $salesPerDay[$d]  = 0;
             }
        }
        return $salesPerDay;
    }

    private function getProductsReport($auth , $request)
    {
        $vendor_id = $auth->id ;

        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }
        $products = Product::where('vendor_id', $vendor_id)
            ->with('orderproducts');

        if ($request->has('category') && !is_null($request->category)) {
            $chkCategory = Category::whereIn('parent_id',is_array($request->category) ? $request->category : [$request->category])->pluck('id');

            if(sizeof($chkCategory)>0)
            {

                $request->category = $chkCategory;

                $products->whereHas('categories', function ($q) use ($request , $chkCategory) {
                    $q->whereIn('id', $chkCategory);
                });
            }
            else {

                $request->category = [ $request->category ];
                $products->whereHas('categories', function ($q) use ($request) {
                    $q->whereIn('id', is_array($request->category) ? $request->category : [$request->category]);
                });
            }
        }



        return $products->orderBy('id', 'DESC')->get();
    }
}
