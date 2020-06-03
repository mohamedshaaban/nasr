<?php

namespace App\Admin\Controllers\Products;

use App\Http\Controllers\Controller;

use App\Models\Product;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Carbon\Carbon;
use App\Models\Option;


class OptionsController extends Controller
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
            // ->title('title')
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
        $grid = new Grid(new Option);

        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
        });

        $grid->id('ID')->sortable();
        $grid->name_en('name_en');
        $grid->name_ar('name_en');


        // $grid->status('Active')->switch($states);

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
        $show = new Show(Option::findOrFail($id));

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
        $form = new Form(new Option);
        $form->tab('Option', function ($form) {
            $form->display('id', 'ID');
            $form->text('name_en', 'Name English');
            $form->text('name_ar', 'Name Arabic');
            $form->select('type', 'type')->options(['radio' => 'radio']);
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        })->tab('Values', function ($form) {
            $form->hasMany('optionvalue', 'Option Values', function (Form\NestedForm $form) {
                $form->text('value_en')->rules('required');
                $form->text('value_ar')->rules('required');

                $form->image('image');
            });
        });

        $form->saved(function ($form) {
            $this->afterSave($form);
        });
        return $form;
    }

    public function afterSave(Form $form)
    {

    }
}
