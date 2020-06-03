<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Vendors;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Validator;
use Session;
use App\Models\ProductOfferRequests;
use App\Models\ProductOffer;
use App\Models\VendorChangeRequestLogs;
use Auth;
class OfferController extends Controller
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
            if( $permission == Vendors::VENDOR_FULL_ACCESS || $permission == Vendors::VENDOR_PRODUCT_ACCESS)
            {

                return $next($request);

            }
            return redirect('/vendor/404');
        });

    }
    public function index()
    {
        $auth = \Auth::guard('vendor')->user();
        $vendor_id = $auth->id ;

        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }

        $productOffers = ProductOffer::where('vendor_id' , $vendor_id)->with('product','product.vendor')
            ->get();

        return view('vendors.offers.index')->with([
            'productOffers' => $productOffers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auth = \Auth::guard('vendor')->user();
        $vendor_id = $auth->id ;

        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }

        $products = \DB::table('products')->where('vendor_id', $vendor_id)
            ->get();

        return view('vendors.offers.create')->with([
            'products' => $products
        ]);
    }
public function edit($offerId)
{
    $offer = ProductOffer::find($offerId);

    $auth = \Auth::guard('vendor')->user();
    $vendor_id = $auth->id ;

    if($auth->parent_id != 0)
    {
        $vendor_id = $auth->parent_id;
    }
    $products = \DB::table('products')->where('vendor_id', $vendor_id)
        ->get();

    return view('vendors.offers.edit')->with([
        'products' => $products,
        'offer'=>$offer
    ]);
}
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->offerRequestvalidation($request->all())->validate();
        $auth = \Auth::guard('vendor')->user();

        $vendor_id = $auth->id ;
        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }
        $offer = ProductOffer::updateOrCreate(['id'=>$request->get('offerId')],[
            'product_id' => $request->get('products'),
            'vendor_id' => $auth->id,
            'percentage' => $request->get('percentage'),
            'fixed' => $request->get('fixed'),
            'from' => date('Y-m-d', strtotime($request->get('from_date'))),
            'to' => date('Y-m-d', strtotime($request->get('to_date'))),
            'status' => 1,
            'vendor_id'=>$vendor_id,
            'is_fixed' =>  $request->get('is_fixed'),
        ]);

//        VendorChangeRequestLogs::addOffer($auth, $products);

        Session::flash('success', 'The offer request has been added successfully');
        return redirect(route('vendor.offer.index'));
    }




    protected function offerRequestvalidation($data)
    {
        return Validator::make($data, [
            'products' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
    }
    protected function destroy(Request $request)
    {
        
         $offer = ProductOffer::where('id' , $request->id)->delete();
Session::flash('success', 'The offer request has been deleted successfully');
        return redirect(route('vendor.offer.index'));
    }

}
