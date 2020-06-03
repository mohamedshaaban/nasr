<?php

namespace App\Http\Controllers\Products;

use App\Models\Category;
use App\Models\ProductCategories;
use App\Models\ProductReview;
use App\Models\ProductSizes;
use App\Models\Sizes;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductOffer;
use App\Http\Controllers\Controller;
use App\Models\Vendors;
use Carbon\Carbon;
use Auth;
class ProductsController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        // dd($request);
        $intcat = null;
        $categoryname = 'Products';
        if(isset($request->categories))
        {
            $categoryname = Category::find($request->categories)->name;

            $intcat = $request->categories;
            $request->categories = $request->categories;
        }

        $selectedcategories = [];
        $products = Product::with(['vendor','categories','offer']);
        $products->where('status',1)->whereHas('vendor', function ($q) use ($request) {
            $q->where('is_approved', 1);
        });
//        $products  = Product::where('status',1)->with('vendor','categories','offer')->paginate(15);
        if ($request->has('new') && !is_null($request->new)) {
            $products->whereDate('created_at', '>=', Carbon::now()->subDays(app('settings')->new_arrival_days)->toDateTimeString());
        }
        if ($request->has('qt') && !is_null($request->new)) {
            $products->where('name_en','like','%'.$request->q.'%')->orWhere('name_ar','like','%'.$request->q.'%')->orWhere('short_description_en','like','%'.$request->q.'%')->orWhere('short_description_ar','like','%'.$request->q.'%');
        }
        if(isset($request->categories))
        {
            $chkCategory = Category::whereIn('parent_id',is_array($request->categories) ? $request->categories : [$request->categories])->pluck('id');

            if(sizeof($chkCategory)>0)
            {

               $request->categories = $chkCategory;
                $selectedcategories = $chkCategory;
                $chkCategory->push($intcat);
                $products->whereHas('categories', function ($q) use ($request , $chkCategory) {
                    $q->whereIn('id', $chkCategory);
                });
            }
            else {

                $selectedcategories = [ (int)$request->categories ];

                $products->whereHas('categories', function ($q) use ($request) {
                    $q->whereIn('id', is_array($request->categories) ? $request->categories : [$request->categories]);
                });
            }
        }

        $products = $products->paginate(100);

        $categories = Category::where('parent_id',0)->where('status',1)->where('hide_filter',0)->with('subCategories')->get();
        $vendors = Vendors::where('parent_id',  0)->where('is_approved',1)->get();

        return view('products.index')->with([
            'categoryname' => $categoryname,
            'products' => $products,
            'categories' => $categories,
            'vendors' =>$vendors,
            'offers'=>false,
            'selectedCategories'=> json_encode($selectedcategories ? $selectedcategories : [])

        ]);
    }
    public function chooseProduct(Request $request)
    {
session(
    [
        'order_requester'=>$request->order_requester,
        'order_destination'=>$request->order_destination,
        'order_category'=>$request->order_category,
        'order_extra'=>$request->order_extra
    ]
);
        // dd($request);
        $intcat = null;
        $categoryname = 'Products';
        if(isset($request->categories))
        {
            $categoryname = Category::find($request->categories)->name;

            $intcat = $request->categories;
            $request->categories = $request->categories;
        }

        $selectedcategories = [];
        $products = Product::with(['vendor','categories','offer']);
        $products->where('status',1);
        $products->whereHas('requesters', function ($q) use ($request) {
            $q->whereIn('id', is_array($request->order_requester) ? $request->order_requester : [$request->order_requester]);
        });
//        $products  = Product::where('status',1)->with('vendor','categories','offer')->paginate(15);
        if ($request->has('new') && !is_null($request->new)) {
            $products->whereDate('created_at', '>=', Carbon::now()->subDays(app('settings')->new_arrival_days)->toDateTimeString());
        }
        if ($request->has('qt') && !is_null($request->new)) {
            $products->where('name_en','like','%'.$request->q.'%')->orWhere('name_ar','like','%'.$request->q.'%')->orWhere('short_description_en','like','%'.$request->q.'%')->orWhere('short_description_ar','like','%'.$request->q.'%');
        }
        if(isset($request->categories))
        {
            $chkCategory = Category::whereIn('parent_id',is_array($request->categories) ? $request->categories : [$request->categories])->pluck('id');

            if(sizeof($chkCategory)>0)
            {

               $request->categories = $chkCategory;
                $selectedcategories = $chkCategory;
                $chkCategory->push($intcat);
                $products->whereHas('categories', function ($q) use ($request , $chkCategory) {
                    $q->whereIn('id', $chkCategory);
                });
            }
            else {

                $selectedcategories = [ (int)$request->categories ];

                $products->whereHas('categories', function ($q) use ($request) {
                    $q->whereIn('id', is_array($request->categories) ? $request->categories : [$request->categories]);
                });
            }
        }

        $products = $products->paginate(100);

        $categories = Category::where('parent_id',0)->where('status',1)->where('hide_filter',0)->with('subCategories')->get();
        $vendors = Vendors::where('parent_id',  0)->where('is_approved',1)->get();

        return view('products.index')->with([
            'categoryname' => $categoryname,
            'products' => $products,
            'categories' => $categories,
            'vendors' =>$vendors,
            'offers'=>false,
            'selectedCategories'=> json_encode($selectedcategories ? $selectedcategories : [])

        ]);
    }
    public function search(Request $request)
    {
$selectedcategories = [];
        $products  = Product::whereHas('vendor', function ($q) use ($request) {
            $q->where('is_approved', 1);
        })->where('name_en','like','%'.$request->qt.'%')->orWhere('name_ar','like','%'.$request->qt.'%')->orWhere('short_description_en','like','%'.$request->qt.'%')->orWhere('short_description_ar','like','%'.$request->qt.'%')->where('status',1)->with('categories','vendor','offer')->paginate(15);

        $categories = Category::where('parent_id',0)->where('status',1)->where('hide_filter',0)->with('subCategories')->get();
        $vendors = Vendors::where('id','>',0)->get();
        $categoryname = 'Products';
        return view('products.index')->with([
            'products' => $products,
            'categories' => $categories,
            'vendors' =>$vendors,
            'categoryname' => $categoryname,
            'offers'=>false ,
                       'selectedCategories'=> json_encode($selectedcategories ? $selectedcategories : [])

        ]);
    }
    public function offers(Request $request)
    {
        $products  = Product::whereHas('vendor', function ($q) use ($request) {
            $q->where('is_approved', 1);
        })->whereIn('id',ProductOffer::where('from','<',Carbon::today()->toDateString())->where('to','>',Carbon::today()->toDateString())->pluck('product_id') )->where('status',1)->with('categories','vendor','offer');
        if ($request->has('sorting') && !is_null($request->sorting)) {

            switch ($request->sorting) {
                case 'asc':
                    $products->orderBy('price', 'asc');
                    break;
                case 'desc':
                    $products->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $products->orderBy('created_at', 'desc');
                    break;
            }
        }
        $products = $products->paginate(15);

        $categories = Category::where('parent_id',0)->where('status',1)->where('hide_filter',0)->with('subCategories')->get();
        $vendors = Vendors::where('id','>',0)->get();

        return view('products.index')->with([
            'products' => $products,
            'categories' => $categories,
            'vendors' =>$vendors,
            'offers'=>true,
            'selectedCategories'=>$request->categories ? $request->categories : 0
        ]);
    }
    public function offersFillter(Request $request)
    {
        $products  = Product::whereHas('vendor', function ($q) use ($request) {
            $q->where('is_approved', 1);
        })->whereIn('id',ProductOffer::where('from','<',Carbon::today()->toDateString())->where('to','>',Carbon::today()->toDateString())->pluck('product_id') )->where('status',1)->with('categories','vendor','offer');
        if ($request->has('sorting') && !is_null($request->sorting)) {

            switch ($request->sorting) {
                case 'asc':
                    $products->orderBy('price', 'asc');
                    break;
                case 'desc':
                    $products->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $products->orderBy('created_at', 'desc');
                    break;
                case 'alpaasc':
                    $products->orderBy('name_en', 'asc');
                    break;
                case 'alpadesc':
                    $products->orderBy('name_en', 'desc');
                    break;
            }
        }
        $products = $products->paginate(15);

         return $products;
    }
    public function show(Request $request)
    {
        $product = Product::with([
            'reviews', 'reviews.user',
            'offer','vendor','categories','productsizes' ,'productsizes'

        ])->where('slug_name', $request->slug)->firstOrFail();
        $productVue = Product::with([
            'reviews', 'reviews.user',
            'offer','vendor','categories','productsizes'

        ])->where('slug_name', $request->slug)->first();
        $productVueSizes = Sizes::whereIN('id',ProductSizes::where('product_id',$productVue->id)->pluck('sizes_id')->toArray())->get();
        $productRate =  ProductReview::where('product_id',$product->id)->avg('rate');
        return view('products.show')->with([
            'product' => $product,
            'productVue' =>$productVue,
            'productVueSizes' =>$productVueSizes,
            'productRate' =>$productRate

        ]);

    }

    public function getSizeInfo(Request $request)
    {
        $product = \App\Models\Product::whereId($request->product_id)->with('vendor', 'offer')->first();

if($request->id==0)
{
    $price =  $product->price;

    if ($product->offer) {

        if ($product->offer->is_fixed) {

            $price = $price - $product->offer->fixed;
        } else {

            $price = $price - (($price * $product->offer->percentage) / 100);
        }

    }
    $price = $price > 0 ? $price : 0 ;
    return ['productsize'=>$product,'price'=>$price.' '.__('website.kd_label')];
}
        $productSize = ProductSizes::where('sizes_id',$request->id)->where('product_id',$request->product_id)->first();

        $price = $productSize->price ? $productSize->price : $product->price;

        if ($product->offer) {

            if ($product->offer->is_fixed) {

                $price = $price - $product->offer->fixed;
            } else {

                $price = $price - (($price * $product->offer->percentage) / 100);
            }

        }
        $price = $price > 0 ? $price : 0 ;
        return ['productsize'=>$productSize,'price'=>$price.' '.__('website.kd_label')];
    }

    public function addReview(Request $request)
    {
        $product = Product::find($request->productID);
        $productReviews = ProductReview::updateOrCreate(['user_id'=>Auth::Id(),'product_id'=>$request->productID],['user_id'=>Auth::Id() ,'product_id'=>$request->productID,'rate'=>$request->rate,'comment'=>$request->comment]);
        $request->session()->flash( 'success', "Your rate Has Been Submitted" );
        return redirect(route('website.product.show',$product->slug_name));
    }
    public function filter(Request $request, $clone = false)
    {

        $products = Product::with(['offer','vendor' ]);
        $products->where('status' ,1 )->whereHas('vendor', function ($q) use ($request) {
            $q->where('is_approved', 1);
        });
        if ($request->has('new') && !is_null($request->new)) {
            $products->whereDate('created_at', '>=', Carbon::now()->subDays(app('settings')->new_arrival_days)->toDateTimeString());
        }
// dd($request->categories);
        if ( !is_null($request->categories)) {
            $allCats = $request->categories;

            if(sizeof($request->categories)>1  )
            {
             $categoryname=__('website.produts_label');
            }
            else
            {
                $categoryname =Category::whereIn('id',is_array($request->categories) ? $request->categories : [$request->categories])->first()->name;

            }
            foreach($request->categories as $cat)
            {
                $categoris[]= ($cat);
            }

            $chkCategory = Category::whereIn('parent_id',is_array($categoris) ? $categoris : [$categoris])->pluck('id');

            if(sizeof($chkCategory)>0)
            {
                $request->categories = $chkCategory;
                foreach($allCats     as $cat)
                {
                    $chkCategory->push((int)$cat);
                }
$productCategories  = ProductCategories::whereIn('category_id' , $allCats)->pluck('product_id')->toArray();
                // $products->whereHas('categories', function ($q) use ($request , $chkCategory) {
                //     $q->whereIn('id', $chkCategory);
                // });
$products->whereIn('id' , $productCategories);
            }
            else {

                $request->categories = [ $request->categories ];
                $productCategories  = ProductCategories::whereIn('category_id' , $allCats)->pluck('product_id')->toArray();

                $products->whereIn('id' , $productCategories);
            }


        }
        else
        {
            $categoryname=__('website.produts_label');
        }



        if ($request->has('vendors') && !is_null($request->vendors)) {
            $products->whereIn('vendor_id',$request->vendors );
        }

        if ($request->has('qt') && !is_null($request->qt)) {
            $value = is_array($request->qt) ? $request->qt[0] : $request->qt;

            if (!is_null($value)) {
                $products->Where(function($q) use ($value) {
                    $q->Where('name_en', 'like', $value . '%');
                    // $q->orwhere('name_ar', 'like', $value . '%');
                });

            }
        }

        if ($request->has('sorting') && !is_null($request->sorting)) {
            switch ($request->sorting) {
                case 'asc':
                    $products->orderBy('price', 'asc');
                    break;
                case 'desc':
                    $products->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $products->orderBy('created_at', 'desc');
                    break;
                case 'latest':
                    $products->orderBy('created_at', 'asc');
                    break;
                case 'alpaasc':
                    $products->orderBy('name_en', 'asc');
                    break;
                case 'alpadesc':
                    $products->orderBy('name_en', 'desc');
                    break;
            }
        }
        if ($clone) {
            // return $products;
        }
         $allProducts = $products->paginate(100);

        return ['products'=>$allProducts,'categoryname'=>$categoryname];
    }
}
