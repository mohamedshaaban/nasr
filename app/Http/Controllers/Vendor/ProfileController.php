<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Countries;
use App\Models\Governorate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Vendors;
 use Session;
use Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
class ProfileController extends Controller
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
            if( $permission == Vendors::VENDOR_FULL_ACCESS )
            {

                return $next($request);

            }
            return redirect('/vendor/404');
        });

    }
    public function index()
    {

        $auth = \Auth::guard('vendor')->user();
        $countries = Countries::all();
        $governorates = Governorate::all();


        return view('vendors.profile.index', [
            'user' => $auth,
            'countries'=>$countries,
            'governorates'=>$governorates
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

        $this->validation($request->all())->validate();

        $logo = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logo1 = 'vendors/' . time() . md5($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
            $path = public_path() . '/uploads/vendors/';
            $file->move($path, $logo1);
            $logo = $logo1;
        }


       $auth = \Auth::guard('vendor')->user();
        if($request->password!=''&&$request->password!=null) {
            $changeProfileRequest = Vendors::updateOrCreate(['id' => $auth->id], [
                'logo' => $logo ? $logo : $auth->logo,
                'email' => $request->get('email'),
                'name' => $request->get('name'),
                'name_ar' => $request->get('name_ar'),
                'password' => Hash::make($request->password),
                'overview_en' => $request->get('overview_en'),
                'overview_ar' => $request->get('overview_ar'),
                'code' => $request->get('code'),
                'country_id' => $request->get('country_id'),
                'address' => $request->get('address'),
                'phone' => $request->get('phone'),
                'governorate_id' => $request->get('governorate_id'),
                'longitude' => $request->get('longitude'),
                'latitude' => $request->get('latitude'),

            ]);
        }
        else
        {
            $changeProfileRequest = Vendors::updateOrCreate(['id' => $auth->id], [
                'logo' => $logo ? $logo : $auth->logo,
                'email' => $request->get('email'),
                'name' => $request->get('name'),
                'name_ar' => $request->get('name_ar'),
                'overview_en' => $request->get('overview_en'),
                'overview_ar' => $request->get('overview_ar'),
                'code' => $request->get('code'),
                'country_id' => $request->get('country_id'),
                'address' => $request->get('address'),
                'phone' => $request->get('phone'),
                'longitude' => $request->get('longitude'),
                'latitude' => $request->get('latitude'),
                'governorate_id' => $request->get('governorate_id'),

            ]);
        }



        Session::flash('success', 'Your request has been sent successfully');
        return redirect()->back();
    }


    protected function validation($data)
    {

         return Validator::make($data, [
            'name' => 'required|string|max:190',
            'name_ar' => 'required|string|max:190',
             'country_id'=>'required',
             'phone'=>'required',
             'address'=>'required',
             'governorate_id'=>'required',

        ]);

    }



}
