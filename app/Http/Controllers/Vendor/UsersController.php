<?php
/**
 * Created by PhpStorm.
 * User: sh3ban
 * Date: 3/31/19
 * Time: 11:25 AM
 */
namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Vendors;
use Session;
use Validator;
use Illuminate\Support\Facades\Hash;
use Auth;

class UsersController extends Controller
{
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


        return view('vendors.users.index', [
            'user' => $auth,
        ]);
    }
    public function create()
    {
        $record = new Vendors();
        $user = (object)array_combine( $record->getFillable(), array_fill( 0, count( $record->getFillable() ), '' ) );

        return view('vendors.users.create')->with('user',$user)->with('create',true);
    }


    public function edit($userId)
    {

        $user = Vendors::whereId($userId)->first();

//        $user = (object)array_combine( $record->getFillable(), array_fill( 0, count( $record->getFillable() ), '' ) );

        return view('vendors.users.create')->with('user',$user)->with('create',false);
    }

    public function store(Request $request)
    {

//        $this->validation($request->all())->validate();

        $logo = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logo = 'vendors/' . time() . md5($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
            $path = public_path() . '/uploads/vendors/';
            $file->move($path, $logo);
        }


        $auth = \Auth::guard('vendor')->user();
        $vendor_id = $auth->id ;

        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }

        if($request->password!=''&&$request->password!=null) {
            $changeProfileRequest = Vendors::updateOrCreate(['id' => $request->vendor_id], [
                'logo' => $logo ? $logo : $auth->logo,
                'email' => $request->get('email'),
                'name' => $request->get('name_en'),
                'name_ar' => $request->get('name_ar'),
                'password' => Hash::make($request->password),
                'overview_en' => $request->get('overview_en'),
                'overview_ar' => $request->get('overview_ar'),
                'is_active' => $request->get('is_active'),
                'parent_id' => $vendor_id,
                'permission' => $request->get('permission'),


            ]);
        }
        else
        {
            $changeProfileRequest = Vendors::updateOrCreate(['id' => $request->vendor_id], [
                'logo' => $logo ? $logo : $auth->logo,
                'email' => $request->get('email'),
                'name' => $request->get('name_en'),
                'name_ar' => $request->get('name_ar'),
                'overview_en' => $request->get('overview_en'),
                'overview_ar' => $request->get('overview_ar'),
                'is_active' => $request->get('is_active'),
                'parent_id' => $vendor_id,
                'permission' => $request->get('permission'),


            ]);
        }



        Session::flash('success', 'Your request has been sent successfully');
        return redirect(route('vendor.user.index'));
    }



}
