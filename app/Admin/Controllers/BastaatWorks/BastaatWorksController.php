<?php

namespace App\Admin\Controllers\BastaatWorks;

use App\Http\Controllers\Controller;

use App\Models\BastaatWorks;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class BastaatWorksController extends Controller
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
            ->title('title_en')
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
        $grid = new Grid(new BastaatWorks);



        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
        });
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('title_en', 'Name');
            $filter->scope('is_active', 'active')->where('status', true);
            $filter->scope('not_active', 'not active')->where('status', false);

        });
        $grid->id('ID')->sortable();
        $grid->title_en('Title English');
        $grid->title_ar('Title Arabic');
        $grid->column('Status')->display(function () {
            if ($this->status == 1) {
                return "<span style='color:green;'>Active</span>";
            } else {
                return "<span style='color:red;'>Not active</span>";
            }
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
        $show = new Show(BastaatWorks::findOrFail($id));

        $show->id('ID');
        $show->title_en('Title');

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
        $form = new Form(new BastaatWorks);

        $form->display('id', 'ID');
        $form->text('title_en', 'Title English')->required();
        $form->text('title_ar', 'Title Arabic')->required();
        $form->image('image', 'Image')->move('images/BastaatWorks')->uniqueName();
        $form->radio('status', 'Status')->options(['1' => 'Avtive', '0' => 'Not Active'])->default('1');
        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');
        $form->tools(function (Form\Tools $tools) {
        $tools->disableView();

    });
        return $form;
    }
}
