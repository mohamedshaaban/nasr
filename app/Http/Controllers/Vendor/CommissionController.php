<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\VendorFullDay;

class CommissionController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('vendors.commission.index');
    }

    public function calendarOrders()
    {
        $auth = \Auth::guard($this->vendor_guard)->user();
        
        // get orders groupby order_date
        $ordersObjs = $this->getVendorOrders($auth);
        // get orders for ('today','tommoro' , 'count per day')
        $orders = $this->getOrders($ordersObjs);

        return response()->json([
            'orders' => $orders['orders'],
            'todayOrders' => $orders['todayOrders'],
            'tomorrowOrders' => $orders['tomorrowOrders'],
            'fullDays' => $auth->vendorFullDay
        ]);

    }


    protected function getVendorOrders($auth)
    {
        $ordersObj = Order::with(
            [
                'userAddress' => function ($query) {
                    $query->select('id', 'mobile_no', 'area_id');
                }, 'userAddress.area' => function ($query) {
                    $query->select('id', 'name_en', 'name_ar');
                }, 'user' => function ($query) {
                    $query->select('id', 'name');
                }, 'orderitems' => function ($query) use ($auth) {
                    $query->where('vendor_id', $auth->id);
                }
            ]
        )
            ->whereHas('orderitems', function ($query) use ($auth) {
                $query->where('vendor_id', $auth->id);
            })
            ->select('id', 'order_date', 'payment_status', 'user_id', 'address_id', 'order_type')
            // ->whereIn('id', $ordersIds)
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->order_date)->format('Y-m-d');
            });

        return $ordersObj;
    }

    protected function getOrders($ordersObj)
    {
        $orders = [];
        $todayOrders = [];
        $tomorrowOrders = [];

        foreach ($ordersObj as $order_date => $ordersArray) {

            // today and tomorrow orders
            if ($order_date == Carbon::today()->format('Y-m-d')) {
                $todayOrders = $ordersArray;
            }
            if ($order_date == Carbon::tomorrow()->format('Y-m-d')) {
                $tomorrowOrders = $ordersArray;
            }
            // end today and tomorrow orders
               
            // orders count per day 
            $countFestivity = 0;
            $countPaid = 0;
            $countNotPaid = 0;

            foreach ($ordersArray as $orderArray) {
                switch ($orderArray->order_type) {
                    case Order::NEW_ORDER:
                        $countFestivity++;
                        break;
                    case Order::OFFLINE_ORDER:
                        if ($orderArray->payment_status == Order::PAYMENT_STATUS_YES) {
                            $countPaid++;
                        } else {
                            $countNotPaid++;
                        }
                        break;
                }
            }
            $orders[] = [
                'order_date' => $order_date,
                'countFestivity' => $countFestivity,
                'countPaid' => $countPaid,
                'countNotPaid' => $countNotPaid
            ];
        // end order count per day
        }

        return [
            'orders' => $orders,
            'todayOrders' => $todayOrders,
            'tomorrowOrders' => $tomorrowOrders
        ];

    }
}

