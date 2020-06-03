<?php

namespace App\Admin\Controllers\Vendors;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use App\Models\Vendors;
use Encore\Admin\Admin;
use App\Models\Governorate;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItems;
use Session;

class DeliveryChargeController extends Controller
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
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

	    $grid = new Grid( new Vendors, function ( $query )  {

			    $query->model()->where( "parent_id", 0 );

	    } );

	    $grid->filter( function ( $filter )   {
		    $filter->disableIdFilter();


			    $filter->in( 'id' , 'name')->select( Vendors::where( "parent_id", 0 )->get()->pluck( 'name', 'id' ) );


	    } );

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
        });

        $grid->id('ID')->sortable();
        $grid->name('Name')->sortable();

        return $grid;
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {

        $governorates = Governorate::with(['areas'])
            ->where('status', 1)
            ->get();

        $vendorAreas = Vendors::with('areas')->where('id', $id)
            ->get()
            ->pluck('areas')
            ->collapse()
            ->pluck('pivot', 'id')
            ->toArray();

        $vendorGovernorates = Vendors::with('areas', 'areas.governorate')
            ->where('id', $id)
            ->get()
            ->pluck('areas')
            ->collapse()
            ->groupBy('governorate_id')
            ->toArray();

        return view('admin.vendors.delivery_charge.edit')->with([
            'governorates' => $governorates,
            'vendorAreas' => $vendorAreas,
            'vendorGovernorates' => $vendorGovernorates,
            'vendor_id' => $id,
            'header' => 'delivery charge'
        ]);
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendors::find($id);

        $vendor->areas()->sync($request->get('delivery_areas'));

        admin_toastr('Delivery Charges for vendor ' . $vendor->name . ' has been updated successfully');
        $request->session()->regenerate();

        return redirect()->back();
    }


    public function export($id)
    {
        $vendor = Vendor::find($id);

        $governorates = Governorate::with(['areas'])
            ->where('status', 1)
            ->orderBy('id', 'DESC')
            ->get();
            // ->pluck('areas', 'id')
            // ->collapse();

        $vendorAreas = Vendor::with('area')->where('id', $vendor->id)
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
            foreach($governorate->areas as $area){
                $list[] = array(
                    $area->id,
                    $area->name_en,
                    $governorate->name_en,
                    isset($vendorAreas[$area->id]) ? $vendorAreas[$area->id]['delivery_charge'] : '',
                    isset($vendorAreas[$area->id]) ? $vendorAreas[$area->id]['minimum_order'] : '',
                );

                $fp = fopen(
                    'vendors/export_excel/vendor_delivery_orders_' . $vendor->id . '.csv',
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
            ->download('vendors/export_excel/vendor_delivery_orders_' . $vendor->id . '.csv', 'vendor_delivery_orders.csv', [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ])
            ->deleteFileAfterSend(true);;
    }

    public function import(Request $request, $id)
    {

        $request->validate([
            'vendor_import_areas' => 'required',
        ]);

        $file = $request->file('vendor_import_areas');
        $extension = $file->getClientOriginalExtension();

        if ($extension != 'csv') {
            admin_error('Error', "The file must be a file of type: csv.");
            return redirect()->back();
        }

        $vendor = Vendor::find($id);
        
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
            while (($row = fgetcsv($handle, 1000 )) !== false) {
        
                if ($header) {
                    $header =false;
                    continue;
                } else {
                    $array =  $row;
                    if (count($array) < 5) {
                        admin_error('Error', "Invalid File Content");
                        return redirect()->back();
                    }
                    if ($array[3] == '' || $array[4] == '') {
                        continue;
                    } else {
                        if (in_array($array[0], $areas) &&
                            (float)$array[3] >= 0 &&
                            (int)$array[4] >= 0) {
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

        admin_toastr('Delivery Charges file has been imported successfully');
        $request->session()->regenerate();

        return redirect()->back();
    }

    public function getAnalyticsAjax(Request $request)
    {
        $vendor = Vendor::find($request->vendor_id);

        $orders = $this->AnalyticsFilter($request, $vendor);

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
        $returnHTML = view('admin.vendors.delivery_charge.analytics_ajax')
            ->with([
                'analytics' => $ordersPerArea,
                'request' => $request
            ])->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));

    }

    protected function AnalyticsFilter(Request $request, $vendor)
    {

        $orders = Order::with('UserAddress')->whereHas('orderItems', function ($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
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




}
