<?php

namespace App\Admin\Controllers\Products;

use App\Http\Controllers\Controller;

use App\Models\OrderRequesters;
use App\Models\ProductSizes;
use App\Models\Vendors;
use App\User;
use Illuminate\Http\Request;
use App\Models\Product;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Character;
use App\Models\Tag;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\Supplier;
use App\Jobs\MoveProductImagesJob;
use App\Jobs\ProductAvailabilityNotificationJob;
use App\Models\Ages;
use App\Models\Breeds;
use App\Models\Genders;
use App\Models\Colors;
use App\Models\Sizes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;


class ProductsController extends Controller
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
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            // ->title('title')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->body($this->formedit($id)->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product);

        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
        });

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->in('in_stock', 'stock')->multipleSelect([1 => 'in stock', 0 => 'out of stock']);
            $filter->like('slug_name', 'slug name');
            $filter->like('vendor_id', 'Vendor')->select(Vendors::where('parent_id',0)->get()->pluck('name', 'id'));
            $filter->like('product_type', 'Type')->select(['0' => 'Normal', '1' => 'Box', '3' => 'Felline', '4' => 'Carton', '5' => 'Sack', '2' => 'Live','6'=>'Bale','7'=>'Band']);

            $filter->scope('is_active', 'active')->where('status', true);
            $filter->scope('not_active', 'not active')->where('status', false);
        });
        $grid->model()->orderBy('id', 'desc');
        $grid->id('ID')->sortable();

        $grid->name_en('name_en');
        $grid->name_ar('name_en');

        $states = [
            'on' => ['value' => 0, 'text' => 'no', 'color' => 'danger'],
            'off' => ['value' => 1, 'text' => 'yes', 'color' => 'success'],
        ];
        $grid->status('Active')->switch($states);
        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
        });
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Product::findOrFail($id));

        $show->id('ID');

        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id = null)
    {
        $form = new Form(new Product);
//        $form->setView('admin.product.view');

        $form->tab('Product Informations', function ($form) {
            $form->display('id', 'ID');
            $form->text('name_en', 'Name English')->required();
            $form->text('name_ar', 'Name Arabic')->required();
            $form->text('slug_name')->required();
            $form->text('sku')->required();

            $categories = Category::all()->pluck('name_en', 'id');
            $form->multipleSelect('categories')->options($categories);
            $requesters = OrderRequesters::all()->pluck('title', 'id');
            $form->multipleSelect('requesters')->options($requesters);


            $vendors = Vendors::all()->pluck('name', 'id');
            $form->select('vendor_id', 'Vendor')->options($vendors);

            $form->select('product_type', 'Type')->options(['0' => 'Normal', '1' => 'Box', '3' => 'Felline', '4' => 'Carton', '5' => 'Sack', '2' => 'Live','6'=>'Bale','7'=>'Alshida'])->required();

            $form->textarea('short_description_en')->required();
            $form->textarea('short_description_ar')->required();
            $form->textarea('description_en')->required();
            $form->textarea('description_ar')->required();
            $form->text('code');

            $form->text('price')->required();
            $form->number('quantity')->required();
            $form->radio('in_stock', 'stock')->options([1 => 'in stock', 0 => 'out of stock']);

            $form->radio('status', 'active')->options(['0' => 'no', '1' => 'yes']);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        })->tab('images', function ($form) {
            $form->image('main_image', 'Image ')->move(Carbon::now()->year . '/products')->uniqueName()->removable();
            $form->multipleImage('images')->move(Carbon::now()->year . '/products')->uniqueName()->removable();;
            $form->html('<script>$(\'[href="#tab-form-3"]\').closest(\'li\').hide();</script>');
            $form->html('<script>$(\'[href="#tab-form-4"]\').closest(\'li\').hide();</script>');
            $form->html($this->javascript());
        })
            ->tab('Sizes',  function ($form) {

                $form->hasMany('productsizes', 'Sizes', function (Form\NestedForm $form) {
                    $sizes = Sizes::all()->pluck('name_en', 'id');
                    $form->text('description', 'Description');
                    $form->text('price', 'Price');

                    $form->select('sizes_id', 'Sizes')->options($sizes);
                });

            },false)
            ->tab('live', function ($form) {

                $breeds = Breeds::all()->pluck('name_en', 'id');
                $form->multipleSelect('breeds', 'Breeds')->options($breeds);

                $genders = Genders::all()->pluck('name_en', 'id');
                $form->multipleSelect('genders', 'Genders')->options($genders);

                $ages = Ages::all()->pluck('name_en', 'id');
                $form->multipleSelect('ages', 'Ages')->options($ages);

                $colors = Colors::all()->pluck('name_en', 'id');
                $form->multipleSelect('colors', 'Colors')->options($colors);

            })
            ->tab('Reviews', function ($form) {
                $form->hasMany('reviews', 'Reviews', function (Form\NestedForm $form) {
                    $form->select('user_id', 'User')->options(User::all()->pluck('name', 'id'));
                    $form->number('rate', 'Rate');
                    $form->textarea('comment', 'Comment');
                });
            });


        $form->saved(function ($form) {
            $this->afterSave($form);
        });
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();

        });
        return $form;
    }

    protected function formedit($id = null)
    {
        $form = new Form(new Product);
        $product = Product::find($id);

//        $form->setView('admin.product.view');

        $form->tab('Product Informations', function ($form) {
            $form->display('id', 'ID');
            $form->text('name_en', 'Name English')->required();
            $form->text('name_ar', 'Name Arabic')->required();
            $form->text('slug_name')->required();
            $form->text('sku')->required();

            $categories = Category::all()->pluck('name_en', 'id');
            $form->multipleSelect('categories')->options($categories);

            $requesters = OrderRequesters::all()->pluck('title', 'id');
            $form->multipleSelect('requesters')->options($requesters);


            $vendors = Vendors::all()->pluck('name', 'id');
            $form->select('vendor_id', 'Vendor')->options($vendors);

            $form->select('product_type', 'Type')->options(['0' => 'Normal', '1' => 'Box', '3' => 'Felline', '4' => 'Carton', '5' => 'Sack', '2' => 'Live','6'=>'Bale','7'=>'Alshida'])->required();
            $form->html($this->javascript());


            $form->textarea('short_description_en')->required();
            $form->textarea('short_description_ar')->required();
            $form->textarea('description_en')->required();
            $form->textarea('description_ar')->required();

            $form->text('price')->required();
            $form->number('quantity')->min(0)->required();
            $form->radio('status', 'active')->options(['0' => 'no', '1' => 'yes']);
            $form->radio('in_stock', 'stock')->options([1 => 'in stock', 0 => 'out of stock']);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        })->tab('images', function ($form) {
            $form->image('main_image', 'Image ')->uniqueName()->removable();
            $form->multipleImage('images')->uniqueName()->removable();;
        });
        if ($product->product_type == 0) {
            $form->html('<script>$(\'[href="#tab-form-3"]\').closest(\'li\').hide();</script>');
            $form->html('<script>$(\'[href="#tab-form-4"]\').closest(\'li\').hide();</script>');
        } else if ($product->product_type == 1||$product->product_type == 3||$product->product_type == 4||$product->product_type == 5) {
            $form->html('<script>$(\'[href="#tab-form-4"]\').closest(\'li\').hide();</script>');
        } else if ($product->product_type == 2) {
            $form->html('<script>$(\'[href="#tab-form-3"]\').closest(\'li\').hide();</script>');
        }
        $form->tab('Sizes' , function ($form) use ($product){

            $form->hasMany('productsizes', 'Sizes',function ($form)use ($product) {

                $sizes = Sizes::where('type_id',$product->product_type)->get()->pluck('name_en', 'id');
                $form->text('description', 'Description');
                $form->text('price', 'Price');

                $form->select('sizes_id', 'Sizes')->options($sizes);
            });

        });


        $form->tab('live', function ($form) {

            $breeds = Breeds::all()->pluck('name_en', 'id');
            $form->multipleSelect('breeds', 'Breeds')->options($breeds);

            $genders = Genders::all()->pluck('name_en', 'id');
            $form->multipleSelect('genders', 'Genders')->options($genders);

            $ages = Ages::all()->pluck('name_en', 'id');
            $form->multipleSelect('ages', 'Ages')->options($ages);

            $colors = Colors::all()->pluck('name_en', 'id');
            $form->multipleSelect('colors', 'Colors')->options($colors);

        })
            ->tab('Reviews', function ($form) {
                $form->hasMany('reviews', 'Reviews', function (Form\NestedForm $form) {
                    $form->select('user_id', 'User')->options(User::all()->pluck('name', 'id'));
                    $form->number('rate', 'Rate');
                    $form->textarea('comment', 'Comment');
                });
            });

        $form->saved(function ($form) {
            $this->afterSave($form);
        });
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();

        });
        return $form;
    }

    public function javascript()
    {
        return view('admin.product.view')
            ->render();
    }

    public function beforSave(Form $form)
    {
    }

    public function afterSave(Form $form)
    {
//        $productModel = $form->model();
//        if (request('main_image')) {
//            MoveProductImagesJob::dispatch($productModel, true);
//        }
//        if (request('images')) {
//            MoveProductImagesJob::dispatch($productModel, false);
//        }


    }
    public function getTypeSizes(Request $request)
    {

        $types = Sizes::where('type_id' ,  $request->type_id )->get();
        return $types;
    }


    public function createToke()
    {
        $ch = curl_init();
        $fields = [
            'email'=>'info@masdr.me',
            'password'=>'Masdr2030'
        ];
        $data_string = json_encode($fields);
        curl_setopt($ch, CURLOPT_URL,"https://app.skuvault.com/api/gettokens");
//        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

// Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = (curl_exec($ch));
        $server_output_arr = json_decode($server_output, true);

$TenantToken =$server_output_arr['TenantToken'];
$UserToken =$server_output_arr['UserToken'];
return ['TenantToken'=>$TenantToken,'UserToken'=>$UserToken];
        curl_close ($ch);
    }
    public function importProducts(Request $request)
    {
        $TenantToken = $this->createToke()['TenantToken'];
        $UserToken = $this->createToke()['UserToken'];
        $ch = curl_init();
        $fields = [
            'TenantToken'=>$TenantToken,
            'UserToken'=>$UserToken
        ];
        $data_string = json_encode($fields);
        curl_setopt($ch, CURLOPT_URL, "https://app.skuvault.com/api/products/getProducts");
//        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

// Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = (curl_exec($ch));
        $server_output_arr = json_decode($server_output, true);
        dd($server_output_arr['Products'] );
        foreach($server_output_arr['Products'] as $product) {


            $productin = (Product::updateOrCreate([
                'sku'=>$product['Sku']
            ],[
                'sku'=>$product['Sku'],
                'name_en'=>$product['Description'],
                'name_ar'=>$product['Description'],
                'slug_name'=>$product['Sku'],
                'price'=>$product['RetailPrice'],
                'short_description_en'=>$product['ShortDescription'],
                'short_description_ar'=>$product['ShortDescription'],
                'description_en'=>$product['Description'],
                'description_ar'=>$product['Description'],
                 'quantity'=>$product['QuantityInStock'],
                'main_image'=>$product['Pictures'][0],
                'images'=>$product['Pictures'],
                'status'=>0,
                'vendor_id'=>1,
                'product_type'=>3,
                'code'=>$product['Code']

            ]));
            foreach ($product['Attributes'] as $attribute){
                $productSize = Sizes::create([
                    'name_en'=>$attribute['Name'],
                    'name_ar'=>$attribute['Name']
                ]);
                $productSize = ProductSizes::updateOrCreate(
                    ['product_id'=>$productin->id ,'sizes_id'=>$productSize->id, 'description'=>$attribute['Name']
                        ,'price'=>$attribute['Value']]
                );
            }

        }
dd($server_output_arr);
        curl_close ($ch);

    }
}
