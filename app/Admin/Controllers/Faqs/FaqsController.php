<?php

namespace App\Admin\Controllers\Faqs;

use App\Http\Controllers\Controller;

use App\Models\Faq;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

use Illuminate\Http\Request;

class FaqsController extends Controller
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
        $grid = new Grid(new Faq);

        $grid->filter(function ($filter) {
            $filter->in('question_en', 'question_ English')->multipleSelect(\App\Models\Faq::all()->pluck('question_en', 'question_en'));
            $filter->scope('is_active', 'active')->where('status', true);
            $filter->scope('not_active', 'not active')->where('status', false);

        });

        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
        });

        $grid->id('ID')->sortable();
        $grid->question_en('Title');

        $states = [
            'on' => ['value' => 0, 'text' => 'disabled', 'color' => 'danger'],
            'off' => ['value' => 1, 'text' => 'enabled', 'color' => 'success'],

        ];
        $grid->status('Status')->switch($states);

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
        $show = new Show(Faq::findOrFail($id));

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
        $form = new Form(new Faq);

        $form->display('id', 'ID');
        $form->text('question_en', 'Question En')->required();
        $form->text('question_ar', 'Question Ar')->required();
        $form->textarea('answer_en', 'Answer En')->required();
        $form->textarea('answer_ar', 'Answer Ar')->required();
        


//        $form->select('Posts_id','Posts')->options(Posts::all()->pluck('name_en', 'id'));
//        $form->radio('status', 'Status')->options(['1' => 'Avtive', '0'=> 'Not Active'])->default('1');

        $states = [
            'on' => ['value' => 0, 'text' => 'disabled', 'color' => 'danger'],
            'off' => ['value' => 1, 'text' => 'enabled', 'color' => 'success'],

        ];
//        $form->status()->switch($statesss);

        $form->switch('status', 'status')->states($states);


        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        return $form;
    }

}
