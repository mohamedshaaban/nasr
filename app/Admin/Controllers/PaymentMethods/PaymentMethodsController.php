<?php

namespace App\Admin\Controllers\PaymentMethods;

use App\Http\Controllers\Controller;

use App\Models\PaymentMethods;
use App\Models\PostsCategories;
use App\Models\Tag;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentMethodsController extends Controller
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
        $grid = new Grid(new PaymentMethods);

        $grid->filter(function ($filter) {
            $filter->like('title_en', 'Title English');

        });

        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
        });

        $grid->id('ID')->sortable();
        $grid->title_en('Title');
        $states = [
            'on' => ['value' => 1, 'text' => 'off', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'no', 'color' => 'danger'],
        ];
        $grid->active('Active')->switch($states);


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
        $show = new Show(PaymentMethods::findOrFail($id));

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
        $form = new Form(new PaymentMethods);

        $form->display('id', 'ID');
        $form->text('title_en', 'Title En')->required();
        $form->text('title_ar', 'Title Ar')->required();
        $form->image('image', 'Image ')->move(Carbon::now()->year . '/payment_methods')->uniqueName();
        $form->radio('active', 'Active')->options(['1' => 'yes', '0' => 'no']);

        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');
        $form->saved(function ($form) {
            if (request('image')) {
                $oldPath = $form->model()->image;
                $newPath = Carbon::now()->year . '/payment_methods/' . $form->model()->id . '/' . $this->afterString(Carbon::now()->year . '/payment_methods/', $form->model()->image);

                $form->model()->image = $newPath;
                $form->model()->save();

                $this->moveFile($oldPath, $newPath);
            }
        });
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();

        });
        return $form;
    }

}
