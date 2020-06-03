<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Ages;
use App\Models\Breeds;
use App\Models\Category;
use App\Models\Colors;
use App\Models\Genders;
use App\Models\Product;
use App\Models\ProductSizes;
use App\Models\Sizes;
use App\Models\Vendors;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\VendorFullDay;
use Auth;
class ProductsController extends Controller
{

    /**
     * Show the application dashboard.
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
    public function index(Request $request)
    {
        $auth = \Auth::guard('vendor')->user();

        $vendor_id = $auth->id ;
        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }
        $products = Product::where('vendor_id',$vendor_id)->get();
        if($request->product)
        {
            $products = Product::where('vendor_id',$vendor_id)->where('name_en','like','%'.$request->product.'%')->get();
        }
        

        return view('vendors.products.index')->with(['products'=>$products])->with(['request'=>$request]);
    }
    public function edit($productId)
    {
        $product = Product::whereId($productId)->with(['breeds','ages','colors','genders','productsizes','categories','reviews'])->first();
        $breeds  =Breeds::all();
        $ages = Ages::all();
        $colors = Colors::all();
        $genders = Genders::all();
        $sizes = Sizes::where('type_id',$product->product_type)->get();
        $categories = Category::where('parent_id' , 0)->get();

        $subcategories = Category::whereIn('parent_id' ,  $product->categories->pluck('id')->toArray())->get();

        $auth = \Auth::guard('vendor')->user();
        $vendor_id = $auth->id ;

        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }

        return view('vendors.products.edit')->with(compact('product' , 'breeds','ages','colors','genders','sizes','subcategories' ,'categories' ,'vendor_id'));
    }
    public function create()
    {
        $record = new Product();

        $product = (object)array_combine( $record->getFillable(), array_fill( 0, count( $record->getFillable() ), '' ) );

        $breeds  =Breeds::all();
        $ages = Ages::all();
        $colors = Colors::all();
        $genders = Genders::all();
        $sizes = Sizes::all();
        $categories = Category::where('parent_id' , 0)->get();
        $subcategories = Category::where('parent_id' ,'!=', 0)->get();
        $auth = \Auth::guard('vendor')->user();
        $vendor_id = $auth->id ;

        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }

        return view('vendors.products.edit')->with(compact('product' ,'subcategories' ,'breeds','ages','colors','genders','sizes','categories' ,'vendor_id'));
    }
    public function store(Request $request)
    {

        $auth = \Auth::guard('vendor')->user();
        $vendor_id = $auth->id ;

        if($auth->parent_id != 0)
        {
            $vendor_id = $auth->parent_id;
        }


        $product = Product::updateOrCreate(['id'=>$request->id],[
            'name_en'=>$request->name_en,
            'name_ar'=>$request->name_ar,
            'slug_name'=>$request->slug_name?$request->slug_name:str_replace(' ','_',$request->name_en).(substr(md5(mt_rand()), 0, 7)),
            'sku'=>$request->sku,
            'short_description_en'=>$request->short_description_en,
            'short_description_ar'=>$request->short_description_ar,
            'description_en'=>$request->description_en,
            'description_ar'=>$request->description_ar,
            'price'=>$request->price,
            'quantity'=>$request->quantity,
            'status'=>$request->status,
            'vendor_id'=>$vendor_id,
            'product_type'=>$request->product_type,
            'code'=>$request->code,

        ]);
        $product = Product::find($product->id);

        if ( request()->has( 'main_image' ) ) {

            $image     = request( 'main_image' );
            $imageName = 'products/' . $product->id . '/' . time() . md5( $image->getClientOriginalName() ) . '.' . $image->getClientOriginalExtension();
            $path      = public_path() . '/uploads/products/' . $product->id . '/';
            $image->move( $path, $imageName );

            $product->main_image = $imageName;

        }
        if ( request()->has( 'images' ) ) {
            $images = [];
            foreach ( request( 'images' ) as $image ) {
                $imageName = 'products/' . $product->id . '/' . time() . md5( $image->getClientOriginalName() ) . '.' . $image->getClientOriginalExtension();
                $images[]  = $imageName;
                $path      = public_path() . '/uploads/products/' . $product->id . '/';
                $image->move( $path, $imageName );
            }


            $product->images =  ( $images );


        }
        $product->update();
        $product->refresh();
        $allProductSizes = [];
if($request->productsizes) {
    foreach ($request->productsizes as $productsizes) {
        $tmp = [];
        if (isset($productsizes['value']) && isset($productsizes['description'])) {
            $tmp['product_id'] = $product->id;
            $tmp['sizes_id'] = $productsizes['value'];
            $tmp['price'] = $productsizes['price'];
            $tmp['description'] = $productsizes['description'];
            array_push($allProductSizes, $tmp);
        }
    }
    ProductSizes::where('product_id', $product->id)->delete();
    ProductSizes::insert($allProductSizes);
}
        $categories = [];
        foreach($request->categories as $category)
        {
            if($category) {
                $categories[] = $category;
            }
        }

         $product->categories()->sync($categories);
         $product->breeds()->sync($request->breeds);
        $product->genders()->sync($request->genders);
        $product->ages()->sync($request->ages);
        $product->colors()->sync($request->colors);
      return redirect(route('vendor.product.index'));
    }
    public function delete($productId)
    {

        $user = Product::whereId($productId)->delete();

//        $user = (object)array_combine( $record->getFillable(), array_fill( 0, count( $record->getFillable() ), '' ) );

        return redirect(route('vendor.product.index'));
    }

    public function getSubCategories(Request $request)
    {

        $categories = Category::whereIn('parent_id' , explode(',', $request->parent_id) )->get();
        return $categories;
    }
    public function getTypeSizes(Request $request)
    {

        $types = Sizes::where('type_id' ,  $request->type_id )->get();
        return $types;
    }

    public function getProductPrice(Request $request)
    {
        $product = Product::find($request->id);

        return $product->price;
    }
}

