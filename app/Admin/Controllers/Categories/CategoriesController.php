<?php

namespace App\Admin\Controllers\Categories;

use App\Http\Controllers\Controller;

use App\Models\Category;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Carbon\Carbon;


class CategoriesController extends Controller
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
        $grid = new Grid(new Category);

        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
        });

        $grid->id('ID')->sortable();
        $grid->name_en('name_en');
        $grid->name_ar('name_en');
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name_en', 'Name');

            $filter->scope('is_active', 'active')->where('status', true);
            $filter->scope('not_active', 'not active')->where('status', false);

        });
        $states = [
            'on' => ['value' => 0, 'text' => 'no', 'color' => 'danger'],
            'off' => ['value' => 1, 'text' => 'yes', 'color' => 'success'],
        ];
        $grid->status('Active')->switch($states);

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
        $form = new Form(new Category);

        $form->display('id', 'ID');
        $form->text('name_en', 'Name English');
        $form->text('name_ar', 'Name Arabic');
        $form->image('image', 'Image ')->move(Carbon::now()->year . '/categories')->uniqueName();

        $categories = Category::all()->pluck('name_en', 'id');
        $categories[0] = 'No parent category';
        $form->select('parent_id')->options($categories);
        $form->number('sort_order');
        $form->radio('status', 'Active')->options(['1' => 'yes', '0' => 'no']);
        $form->radio('top')->options(['1' => 'yes', '0' => 'no']);
        $form->radio('home')->options(['1' => 'yes', '0' => 'no']);
        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        $form->saved(function ($form) {
            if (request('image')) {
                $oldPath = $form->model()->image;
                $newPath = Carbon::now()->year . '/categories/' . $form->model()->id . '/' . $this->afterString(Carbon::now()->year . '/categories/', $form->model()->image);

                $form->model()->image = $newPath;
                $form->model()->save();

                $this->moveFile($oldPath, $newPath);
            }
        });
        return $form;
    }
}
