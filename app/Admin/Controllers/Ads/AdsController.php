<?php

namespace App\Admin\Controllers\Ads;

use App\Http\Controllers\Controller;

use App\Models\Ads;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Carbon\Carbon;
use Encore\Admin\Form\Builder;

class AdsController extends Controller
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
        $grid = new Grid(new Ads);
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('title_en', 'Name');
            $filter->scope('is_active', 'active')->where('status', true);
            $filter->scope('not_active', 'not active')->where('status', false);

        });
        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
        });
        
        $grid->id('ID')->sortable();
        $grid->title_en('Title');

        $states = [
            'on' => ['value' => 0, 'text' => 'disabled', 'color' => 'danger'],
            'off' => ['value' => 1, 'text' => 'enabled', 'color' => 'success'],

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
        $show = new Show(Ads::findOrFail($id));

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
        $form = new Form(new Ads);

        $form->display('id', 'ID');
        $form->text('title_en', 'Title En')->required();
        $form->text('title_ar', 'Title Ar')->required();
        $form->text('link', 'Link')->required();
        $form->image('image', 'Image ')->move(Carbon::now()->year . '/ads')->uniqueName()->required();
        $form->image('image_ar', 'Image Ar')->move(Carbon::now()->year . '/ads')->uniqueName()->required();
         $states = [
            'on' => ['value' => 0, 'text' => 'disabled', 'color' => 'danger'],
            'off' => ['value' => 1, 'text' => 'enabled', 'color' => 'success'],

        ];


        $form->switch('status', 'Active')->states($states)->default(1);

        $states = [
            'on' => ['value' => 0, 'text' => 'Yes', 'color' => 'danger'],
            'off' => ['value' => 1, 'text' => 'No', 'color' => 'success'],

        ];


        $form->switch('is_top', 'Show in Top')->states($states);
//        $form->status()->switch($states);
        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');
        $form->saved(function ($form) {
            if (request('image')) {
                $oldPath = $form->model()->image;
                $newPath = Carbon::now()->year . '/ads/' . $form->model()->id . '/' . $this->afterString(Carbon::now()->year . '/ads/', $form->model()->image);

                $form->model()->image = $newPath;
                $form->model()->save();

                $this->moveFile($oldPath, $newPath);
            }
            if (request('image_ar')) {
                $oldPath = $form->model()->image_ar;
                $newPath = Carbon::now()->year . '/ads/' . $form->model()->id . '/' . $this->afterString(Carbon::now()->year . '/ads/', $form->model()->image_ar);

                $form->model()->image_ar = $newPath;
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
