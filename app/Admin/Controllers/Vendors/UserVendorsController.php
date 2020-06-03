<?php
/**
 * Created by PhpStorm.
 * User: sh3ban
 * Date: 4/8/19
 * Time: 10:20 AM
 */
namespace App\Admin\Controllers\Vendors;
 
use App\Admin\Controllers\Countries\CountriesController;
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

class UserVendorsController extends Controller
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
            ->status('status')
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


        $grid->model()->where('parent_id','!=',  0);
        $grid->id('ID')->sortable();
        $grid->name('Name');
        $grid->email('Email');
        $grid->column('Vendor')->display(function () {
            $user = Vendors::find($this->parent_id);
            return '<span><a href="/admin/vendors/' . $user->id  . '/edit">' . $user->email . '</a></span>';
        });

        $grid->actions(function ($actions) {
             $actions->disableView();
        });
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name', 'Name');
            $filter->like('email', 'Email');
            $filter->like('parent_id', 'Vendor')->select(Vendors::where('parent_id',0)->get()->pluck('name', 'id'));

            $filter->scope('is_active', 'active')->where('is_active', true);
            $filter->scope('not_active', 'not active')->where('is_active', false);

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
             $form->password('password', 'Password')->default(function ($form) {
                return $form->model()->password;
            });
            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });
            $form->text('overview_en', 'overview en')->required();
            $form->text('overview_ar', 'overview ar')->required();
            $vendors = Vendors::where('parent_id' , 0 )->get()->pluck('name', 'id');
            $form->select('parent_id', 'vendor')->options($vendors);

            $states = [
                'on' => ['value' => 0, 'text' => 'disabled', 'color' => 'danger'],
                'off' => ['value' => 1, 'text' => 'enabled', 'color' => 'success'],

            ];
            $form->switch('is_active', 'Active')->states($states);

            $form->select('permission', 'Permission')->options([Vendors::VENDOR_FULL_ACCESS=>'Full Access',Vendors::VENDOR_PRODUCT_ACCESS=>'Product Permission',Vendors::VENDOR_SALES_ACCESS=>'Sales Permission']);
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
        return $form;
    }
}
