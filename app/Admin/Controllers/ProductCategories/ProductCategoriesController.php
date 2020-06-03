<?php

namespace App\Admin\Controllers\ProductCategories;

use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\OrderDestination;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Carbon\Carbon;
use Encore\Admin\Form\Builder;

class ProductCategoriesController extends Controller
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
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category);

        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
        });
        $grid->filter(function ($filter) {
            $filter->like('name_en', 'Title English');
            $filter->scope('is_active', 'active')->where('status', true);
            $filter->scope('not_active', 'not active')->where('status', false);
        });
        $grid->id('ID')->sortable();
        $grid->name_en('Title');



        $grid->created_at('Created at');
        $grid->updated_at('Updated at');
        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
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
        $show = new Show(Category::findOrFail($id));

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
        $form = new Form(new Category());

        $form->display('id', 'ID');
        $form->text('name_en', 'Title En')->required();
        $form->text('name_ar', 'Title Ar')->required();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();

        });
        return $form;
    }
}
