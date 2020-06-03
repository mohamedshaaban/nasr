<?php
/**
 * Created by PhpStorm.
 * User: sh3ban
 * Date: 4/8/19
 * Time: 10:20 AM
 */
namespace App\Admin\Controllers\Vendors;
 
use App\Admin\Controllers\Countries\CountriesController;
use App\Models\Area;
use App\Models\Banks;
use App\Models\Countries;
use App\Models\Governorate;
use App\Models\Order;
use App\Models\Vendors;
use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\OrderProductOption;
use App\Models\OrderStatus;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\Settings;
use App\Models\ShippingMethods;
use App\Models\OrderTrack;
use App\Models\UserAddress;
use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class VendorsController extends Controller
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
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
//            ->title('title')
//            ->status('status')
            ->body($this->detail($id));
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
        return $content
            ->header('Edit')

            ->body($this->form()->edit($id));
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
        $grid = new Grid(new Vendors());


        $grid->model()->where('parent_id',  0);
        $grid->id('ID')->sortable();
        $grid->name('Name');
        $grid->email('Email');
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name', 'Name');
            $filter->like('email', 'Email');

            $filter->scope('is_active', 'active')->where('is_active', true);
            $filter->scope('not_active', 'not active')->where('is_active', false);

        });
        $grid->actions(function ($actions) {
            $actions->disableView();
        });
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Vendors::findOrFail($id));

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
    protected function form()
    {
        $form = new Form(new Vendors);
        $form->tab('Vendor Informations', function ($form) {
            $form->display('id', 'ID');
            $form->text('name', 'Name')->required();
            $form->email('email', 'Email')->required();
            $form->text('code', 'Code')->required();
            $form->text('phone', 'phone')->required();
            $form->password('password', 'Password')->default(function ($form) {
                return $form->model()->password;
            });
            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });
            $form->text('overview_en', 'overview en');
            $form->text('overview_ar', 'overview ar');
            $countries = Countries::all()->pluck('name', 'id');
            $governorate = Governorate::all()->pluck('name', 'id');
            $form->select('country_id', 'Country')->options($countries);
            $form->select('governorate_id', 'Governate')->options($governorate);
            $form->text('address', 'addresss');
            $form->latlong('latitude', 'longitude', 'Position');
            $states = [
                'on' => ['value' => 0, 'text' => 'disabled', 'color' => 'danger'],
                'off' => ['value' => 1, 'text' => 'enabled', 'color' => 'success'],

            ];
            $form->switch('is_active', 'Active')->states($states);
            $form->switch('is_approved', 'Approved')->states($states);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        })->tab('Vendor Bank Information', function ($form) {
                $form->hasMany( 'vendorbankinformation', 'Bank Information', function ( Form\NestedForm $form ) {
                    $form->text('account_name','Account Name');
                    $form->text('address','Address');
                    $banks = Banks::all()->pluck('title', 'id');
                    $form->select('bank_id', 'Bank')->options($banks);

                    $form->text('iban','IBAN');
                });
            })->tab('Vendor Commission', function ($form) {
            $form->hasMany( 'vendorcommissions', 'Vendor Commission', function ( Form\NestedForm $form ) {
                $form->text('fixed','Fixed Amount')->default(Settings::first()->default_commission_kwd);
                $form->text('precentage','Precentage')->default(Settings::first()->default_commission_precentage);

            });
        });
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();

        });
        return $form;
    }
}
