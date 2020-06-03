<?php

namespace App\Admin\Controllers\Pages;

use App\Http\Controllers\Controller;

use App\Models\Pages;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Carbon\Carbon;

class PagesController extends Controller
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
            ->title('title')
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
        $grid = new Grid(new Pages);

        $grid->filter(function ($filter) {
            $filter->like('name_en', 'Title English');

         });
        
        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
            $actions->disableDelete();
        });
        
        $grid->id('ID')->sortable();
        $grid->name_en('Title');

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
        $show = new Show(Pages::findOrFail($id));

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
        $form = new Form(new Pages);

        $form->display('id', 'ID');
        $form->text('slug', 'Slug');
        $form->text('name_en', 'Title En')->required();
        $form->ckeditor('description_en', 'Body En')->required();
        $form->text('name_ar', 'Title Ar')->required();
        $form->ckeditor('description_ar', 'Body Ar')->required();
        $form->image('image', 'Image')->move(Carbon::now()->year.'/pages')->uniqueName();;


        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        return $form;
    }
}
