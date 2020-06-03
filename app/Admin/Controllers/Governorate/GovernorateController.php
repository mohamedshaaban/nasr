<?php

namespace App\Admin\Controllers\Governorate;

use App\Http\Controllers\Controller;

use App\Models\Countries;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use App\Models\Tag;
use Encore\Admin\Admin;
use App\Models\Governorate;

class GovernorateController extends Controller
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
        $grid = new Grid(new Governorate);

        $grid->id('ID')->sortable();
        $grid->name_en('Name')->sortable();

        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
        });
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name_en', 'Name');
            $filter->like('country_id', 'Country')->select(Countries::all()->pluck('name_en', 'id'));

            $filter->scope('is_active', 'active')->where('status', true);
            $filter->scope('not_active', 'not active')->where('status', false);

        });
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
        $show = new Show(Governorate::findOrFail($id));

        $show->id('ID')->sortable();


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */

    protected function form()
    {
        $form = new Form(new Governorate);
        $form->tab('Governorate', function ($form) {
            $form->text('name_en', 'Name English')->required();
            $form->text('name_ar', 'Name Arabic')->required();
            $genders = Countries::all()->pluck('name_en', 'id');
            $form->select('country_id', 'Country')->options($genders)->required();
            $form->radio('status', 'Active')->options(['0' => 'No', '1' => 'Yes'])->default(1);
        });
        $form->tab('Areas', function ($form) {
        $form->hasMany('areas', 'Areas', function (Form\NestedForm $form) {
            $form->text('name_en','Name English')->rules('required');
            $form->text('name_ar','Name Arabic')->rules('required');

        });
        });
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();

        });
        return $form;
    }


}
