<?php

namespace App\Admin\Controllers\Teams;

use App\Http\Controllers\Controller;

use App\Models\Ages;
use App\Models\Breeds;
use App\Models\Colors;
use App\Models\Genders;
use App\Models\Teams;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Carbon\Carbon;
use Encore\Admin\Form\Builder;

class TeamsController extends Controller
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
        $grid = new Grid(new Teams);
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('title', 'Name');


        });
        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
        });

        $grid->id('ID')->sortable();
        $grid->title('Title');



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
        $show = new Show(Teams::findOrFail($id));

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
        $form = new Form(new Teams);
        $form->tab('teammemberss', function ($form) {

        $form->text('title', 'Title En')->required();
        });

        $form->tab('Members', function ($form) {
            $form->hasMany('teammemberss', 'teammemberss', function (Form\NestedForm $form) {
            $form->text('name', 'name');
            $form->text('age', 'age');
            $form->text('tshit_size', 'tshit_size');



    });
    });
        $form->tools(function (Form\Tools $tools) {
        $tools->disableView();

    });
        return $form;
    }
}
