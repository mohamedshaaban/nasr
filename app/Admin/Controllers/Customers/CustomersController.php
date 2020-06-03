<?php
/**
 * Created by PhpStorm.
 * User: sh3ban
 * Date: 4/8/19
 * Time: 2:21 PM
 */
namespace App\Admin\Controllers\Customers;

use App\Http\Controllers\Controller;

use App\Models\Area;
use App\Models\Countries;
use App\Models\Governorate;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use App\Models\Tag;
use Encore\Admin\Admin;
use App\User;

class CustomersController extends Controller
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
            ->body($this->form($id)->edit($id));
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
            ->body($this->form(0));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);
 $grid->actions(function ($actions) {

    $actions->disableView();
});
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name', 'Name');
            $filter->like('email', 'Email');

            $filter->scope('is_active', 'active')->where('is_active', true);
            $filter->scope('not_active', 'not active')->where('is_active', false);

        });
        $grid->id('ID')->sortable();
        $grid->name('Name');
        $grid->email('Email');

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
        $show = new Show(User::findOrFail($id));

        $show->id('ID')->sortable();

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */

    protected function form($id = null )
    {
        if($id) {
            $user = User::with(["useraddress" => function ($q) {
                $q->where('is_guest', 0);
            }])->where('id', $id)->first();
        }
        else
        {
            $user = new User();
        }
        $form = new Form($user);


        $form->tab('User Informations', function ($form) {
        $form->text('name' , 'Name');
        $form->email('email' , 'Email');
        $form->datetime('email_verified_at' , 'email verified at');
        $form->password('password', 'Password')->default(function ($form) {
            return $form->model()->password;
        });
        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });
        $form->text('first_name', 'first name');
        $form->text('last_name', 'Last name');
        $form->radio('is_active' , 'Active')->options(['0' => 'No', '1' => 'Yes'])->default(1);
        })->tab('User Address', function ($form) {
            $form->hasMany( 'useraddress', 'Address', function ( Form\NestedForm $form ) {

                $form->display('id', 'ID');
                $form->text('address_type','Address type');
                $form->text('first_name','First Name');
                $form->text('second_name','Second name');
                $form->text('phone_no','Phone');
                
                $form->text('city','City');
                $form->text('zip_code','Zip code');
                $form->text('block','Block');
                $form->text('street','Street');
                $form->text('avenue','Avenue');
                $form->text('fax','Building');
                $form->text('floor','Floor');
                $form->text('flat','Flat ');
                $form->text('extra_direction','Extra Direction');

                $countries = Countries::all()->pluck('name_en', 'id');
                $form->select('country_id', 'Country')->options($countries);
                $governorate = Governorate::all()->pluck('name_en', 'id');
                $form->select('governate_id', 'Governorate')->options($governorate);
                $areas = Area::all()->pluck('name_en', 'id');
                $form->select('area_id', 'Area')->options($areas);


        });
        });
                $form->tools(function (Form\Tools $tools) {
        $tools->disableView();

    });
        return $form;
    }

}
