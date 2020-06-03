<?php

namespace App\Http\Controllers\Vendor;
use Auth;
use App\Models\VendorDeliveryCharges;
use App\Models\Vendors;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Governorate;
use App\Models\Vendor;
use App\Models\Area;
use App\Models\Order;

use Session;
use Illuminate\Support\Facades\Storage;

use Validator;

class DeliveryChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $permission = Auth::guard('vendor')->user()->permission;
            if( $permission == Vendors::VENDOR_FULL_ACCESS)
            {

                return $next($request);

            }
            return redirect('/vendor/404');
        });

    }
    public function index(Request $request)
    {
        $auth = \Auth::guard('vendor')->user();

        $vendor_id = $auth->id ;
        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }

        $governorates = Governorate::with(['areas'])
            ->where('status', 1)
            ->where('country_id',$auth->country_id)
            ->get();

        $vendorAreas = Vendors::with('areas')->where('id', $vendor_id)
            ->get()
            ->pluck('areas')
            ->collapse()
            ->pluck('pivot', 'id')
            ->toArray();

        $vendorGovernorates = Vendors::with('areas', 'areas.governorate')->where('id', $vendor_id)
            ->get()
            ->pluck('areas')
            ->collapse()
            ->groupBy('governorate_id')
            ->toArray();


        return view('vendors.delivery_charge.index', [
            'governorates' => $governorates,
            'vendorAreas' => $vendorAreas,
            'vendorGovernorates' => $vendorGovernorates,
        ]);
    }

    public function getAnalyticsAjax(Request $request)
    {
        $auth = \Auth::guard('vendor')->user();

        $orders = $this->AnalyticsFilter($request, $auth);

        $ordersArea = $orders->groupBy('UserAddress.area.name_en');
        $ordersCount = $orders->count();

        $ordersPerArea = [];
        foreach ($ordersArea as $area => $areaOrders) {
            $ordersPerArea[] =
                [
                'area' => $area,
                'percentage' => ($areaOrders->count() / $ordersCount) * 100 . '%'
            ];
        }
        $returnHTML = view('vendors.delivery_charge.analytics_ajax')
            ->with([
                'analytics' => $ordersPerArea,
                'request' => $request
            ])->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));

    }

    protected function AnalyticsFilter(Request $request, $auth)
    {

        $orders = Order::with('UserAddress')->whereHas('orderProducts', function ($q) use ($auth) {
            $q->where('vendor_id', $auth->id);
        });

        if ($request->from_month != 'null' && $request->from_year != 'null') {
            $orders = $orders->whereDate('order_date', '>=', date('Y-m-d H:i:m', strtotime($request->from_year . '-' . $request->from_month)));
        }
        if ($request->to_month != 'null' && $request->to_year != 'null') {
            $orders = $orders->whereDate('order_date', '<=', date('Y-m-d H:i:m', strtotime($request->to_year . '-' . $request->to_month . '-' . '31')));

        }

        $orders = $orders->get();

        return $orders;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auth = \Auth::guard('vendor')->user();
        $vendor = Vendors::find($auth->id);

        $vendor->areas()->sync($request->get('delivery_areas'));

        Session::flash('success', 'Delivery Charges has been updated successfully');
        return redirect()->back();

    }


    public function export(Request $request)
    {

        $auth = \Auth::guard('vendor')->user();

        $governorates = Governorate::with(['areas'])
            ->where('status', 1)
            ->orderBy('id', 'DESC')
            ->get();


        $vendorAreas = Vendors::with('area')->where('id', $auth->id)
            ->get()
            ->pluck('area')
            ->collapse()
            ->pluck('pivot', 'id')
            ->toArray();


        // export excel 
        $list = array(
            array(
                '#',
                'area',
                'governorate',
                'delivery charge',
                'minimum order'
            ),
        );

        foreach ($governorates as $governorate) {
            foreach($governorate->areas as $area ){
            $list[] = array(
                $area->id,
                $area->name_en,
                $governorate->name_en,
                isset($vendorAreas[$area->id]) ? $vendorAreas[$area->id]['delivery_charge'] : '',
                isset($vendorAreas[$area->id]) ? $vendorAreas[$area->id]['minimum_order'] : '',
            );

            $fp = fopen(
                'vendors/export_excel/vendor_delivery_orders_' . $auth->id . '.csv',
                'w'
            );
            foreach ($list as $fields) {
                fputcsv($fp, $fields);
            }
            fclose($fp);
        }
    }
        // end export excels
        return response()
            ->download('vendors/export_excel/vendor_delivery_orders_' . $auth->id . '.csv', 'vendor_delivery_orders.csv', [
                'Content-Type' => 'text/csv',
                "Pragma" => "no-cache",
                'Content-Disposition' => "attachment; filename='vendor_delivery_orders.csv'",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0",
                ])
            ->deleteFileAfterSend(true);;

    }

    public function import(Request $request)
    {

        $request->validate([
            'vendor_import_areas' => 'required',
        ]);

        $file = $request->file('vendor_import_areas');
        $extension = $file->getClientOriginalExtension();

        if ($extension != 'csv') {
            return redirect()->back()
                ->withErrors(['file' => ['The file must be a file of type: csv.']]);
        }

        $auth = \Auth::guard('vendor')->user();
        $vendor = Vendors::find($auth->id);
        $areas = Governorate::with(['areas'])
            ->where('status', 1)
            ->orderBy('id', 'DESC')
            ->get()
            ->pluck('areas')
            ->collapse()
            ->pluck('id')
            ->toArray();


        // $delimiter = ',';
        $header = true;
        $data = [];

        if (($handle = fopen($file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000)) !== false) {
                if ($header) {
                    $header = false;
                    continue;
                } else {
                    $array = $row;
                    if (count($array) < 5) {
                        return redirect()->back()
                            ->withErrors(['file' => ['Invalid File Content']]);
                    }
                    if ($array[3] == '' || $array[4] == '') {
                        continue;
                    } else {
                        if (in_array($array[0], $areas) &&
                            (float)$array[3] >= 0 &&
                            (float)$array[4] >= 0) {
                            $data[$array[0]] = [
                                'delivery_charge' => $array[3],
                                'minimum_order' => $array[4]
                            ];
                        }
                    }
                }
            }
            fclose($handle);
        }
        if (count($data) > 0) {
            $vendor->area()->sync($data);
        }
        Session::flash('success', 'Delivery Charges has been updated successfully');
        return redirect()->back();
    }


}
