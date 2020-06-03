<?php

namespace App\Admin\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\OrderStatus;
use Validator;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Vendors;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\VendorOrdersDetails;
use App\Models\Finance;

class VendorOrdersController extends Controller
{
    public function index(Request $request)
    {
        $auth = \Auth::guard('admin')->user();


            $vendors = Vendors::all();
            $status = OrderStatus::all();


        $orders = $this->filter($request);
        $allorders = $this->filterall($request);


        return view('admin.reports.vendor_orders.index')->with([
            'orders' => $orders,
            'allorders' => $allorders,
            'vendors' => $vendors,
            'request' => $request,
            'header' => 'Vendor Orders',
            'orderstatus' => $status
        ]);

    }

    protected function filter(Request $request)
    {
	    $user_id = 0;


        $orders = Order::with(['orderproducts', 'User', 'vendororderdeliverycharges' ,'ordertrack'])
	        ->whereIn("id", function ( $q ) use ( $user_id ) {
		        $q->select( 'order_id' );
                    $q->from( 'order_products' );
                $q->join( 'vendors', 'order_products.vendor_id', '=', 'vendors.id');

	        });
	        $orderPaidIds = Order::where('is_paid' , 1)->pluck('id')->toArray();
             $orders->whereIn('id', $orderPaidIds);
        if ($request->has('search') && $request->has('status') && !is_null($request->status)) {
            $orders = $orders->whereHas('ordertrack', function ($q) use ($request) {
                $q->where('order_status_id', $request->status);
            });
        }
        if ($request->has('search') && $request->has('vendor_id') && !is_null($request->vendor_id)) {
            $orders = $orders->whereHas('orderproducts', function ($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id);
            })->with([
                'orderproducts' => function ($query) use ($request) {
                    $query->where('vendor_id', $request->vendor_id);
                }
            ])->whereHas('vendororderdeliverycharges', function ($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id);
            })->with([
                'vendororderdeliverycharges' => function ($query) use ($request) {
                    $query->where('vendor_id', $request->vendor_id);
                }
            ]);
        }

        if ($request->has('search') && $request->has('from_date') && !is_null($request->from_date)) {
            $orders = $orders->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('search') && $request->has('to_date') && !is_null($request->to_date)) {
            $orders = $orders->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $orders->orderBy('created_at', 'desc')
            ->paginate(10);

        return $orders;

    }
    protected function filterall(Request $request)
    {
	    $user_id = 0;


        $orders = Order::with(['orderproducts', 'User', 'vendororderdeliverycharges' ,'ordertrack'])
	        ->whereIn("id", function ( $q ) use ( $user_id ) {
		        $q->select( 'order_id' );
                    $q->from( 'order_products' );
                $q->join( 'vendors', 'order_products.vendor_id', '=', 'vendors.id');

	        });
	        $orderPaidIds = Order::where('is_paid' , 1)->pluck('id')->toArray();
             $orders->whereIn('id', $orderPaidIds);
        if ($request->has('search') && $request->has('status') && !is_null($request->status)) {
            $orders = $orders->whereHas('ordertrack', function ($q) use ($request) {
                $q->where('order_status_id', $request->status);
            });
        }
        if ($request->has('search') && $request->has('vendor_id') && !is_null($request->vendor_id)) {
            $orders = $orders->whereHas('orderproducts', function ($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id);
            })->with([
                'orderproducts' => function ($query) use ($request) {
                    $query->where('vendor_id', $request->vendor_id);
                }
            ])->whereHas('vendororderdeliverycharges', function ($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id);
            })->with([
                'vendororderdeliverycharges' => function ($query) use ($request) {
                    $query->where('vendor_id', $request->vendor_id);
                }
            ]);
        }

        if ($request->has('search') && $request->has('from_date') && !is_null($request->from_date)) {
            $orders = $orders->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('search') && $request->has('to_date') && !is_null($request->to_date)) {
            $orders = $orders->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $orders->orderBy('created_at', 'desc')
            ->get();

        return $orders;

    }

    public function saveAmount(Request $request)
    {
        $rules = [
            'vendor_id' => 'required',
            'month' => 'required',
            'amount_value' => 'required',
            'vendor_profit' => 'required',
            'transferred_amount' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), "code" => 422], 422);
        }

        $auth = \Auth::guard('admin')->user();

        $finance = Finance::create([
            'vendor_id' => $request->vendor_id,
            'admin_user_id' => $auth->id,
            'transferred_amount' => $request->amount_value,
            'date' => date('Y-m-d H:i:m', strtotime($request->month)),
            'remaining_balance' => $request->vendor_profit - ($request->transferred_amount + $request->amount_value),
        ]);


        return response()->json([
            'status' => true,
        ]);
    }

    public function vendorLog(Request $request, $vendor_id)
    {

        $financeLogs = Finance::where('vendor_id', $vendor_id)
            ->whereMonth('date', date('m', strtotime($request->date)))
            ->orderBy('created_at', 'desc')
            ->get();


        return view('admin.finance.finance_log')->with([
            'financeLogs' => $financeLogs,
            'header' => 'Finance Log'
        ]);


    }
}
