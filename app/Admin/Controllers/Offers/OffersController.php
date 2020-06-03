<?php

namespace App\Admin\Controllers\Offers;

use App\Http\Controllers\Controller;

use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use App\Models\Tag;
use Encore\Admin\Admin;
use App\Models\ProductOffer;
use App\Models\Product;

class OffersController extends Controller
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
            // ->status('status')
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
        $grid = new Grid(new ProductOffer);

        $grid->id('ID')->sortable();

        $grid->column('Percantage')->display(function () {
            return "<span>" . $this->percentage . "%" . "</span>";
        });
        $grid->actions(function ($actions) use ($grid) {
            $actions->disableView();
        });
        $grid->from('From Date');
        $grid->to('To Date');


        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('product_id', 'Product')->select(Product::all()->pluck('name_en', 'id'));
            $filter->between('from', 'From')->datetime();
            $filter->between('to', 'To')->datetime();
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
        $show = new Show(ProductOffer::findOrFail($id));

        $show->id('ID')->sortable();
        $show->product()->name_en('Name');
        $show->percentage('Percantage');
        $show->fixed('fixed price');
        $show->from('From Date');
        $show->to('To Date');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */

    protected function form()
    {
        $form = new Form(new ProductOffer);

        $form->select('product_id', 'product Name')->options(Product::all()->pluck('name_en', 'id'))->required();
        $form->number('percentage');
        $form->number('fixed', 'Fixed Price');
        $form->radio('is_fixed')->options(['0' => 'No', '1' => 'Yes'])->default(0);
        $form->date('from')->required();
        $form->date('to')->required();

        $form->saving(function ($form) {
            $productPrice = Product::find($form->product_id)->price;
            if ($form->is_fixed == 1) {
                $form->percentage = ($form->fixed / $productPrice) * 100;
            } else {
                $form->fixed = ($form->percentage / 100) * $productPrice;
            }
        });
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();

        });
        return $form;
    }


}
