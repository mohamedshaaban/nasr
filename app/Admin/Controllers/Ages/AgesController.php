<?php

namespace App\Admin\Controllers\Ages;

use App\Http\Controllers\Controller;

use App\Models\Ages;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Carbon\Carbon;
use Encore\Admin\Form\Builder;

class AgesController extends Controller
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
        $grid = new Grid(new Ages);

        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
        });
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name_en', 'Name');

        });
        $grid->id('ID')->sortable();
        $grid->name_en('Title');
        $grid->name_ar('Title ar');


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
        $show = new Show(Ages::findOrFail($id));

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
        $form = new Form(new Ages);

        $form->display('id', 'ID');
        $form->text('name_en', 'Title En')->required();
        $form->text('name_ar', 'Title Ar')->required();

        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');
        $form->tools(function (Form\Tools $tools) {
        $tools->disableView();

    });
        return $form;
    }
}
