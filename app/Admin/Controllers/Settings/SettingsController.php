<?php

namespace App\Admin\Controllers\Settings;

use App\Http\Controllers\Controller;

use App\Models\Settings;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class SettingsController extends Controller
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
		$grid = new Grid(new Settings);

		$grid->id('ID')->sortable();
		$grid->created_at('Created at');
		$grid->updated_at('Updated at');
		$grid->actions(function ($actions) {
			$actions->disableDelete();
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
		$show = new Show(Settings::findOrFail($id));

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
		$form = new Form(new Settings);

		$form->display('id', 'ID');

		
		$form->text('facebook' ,'Facebook');
		$form->text('twitter' ,'Twitter');
		$form->text('instagram' ,'Instagram');
		$form->textarea('location_en' ,'Location En');
		$form->textarea('location_ar' ,'Location Ar');
		$form->text('phone' ,'Phone');
		$form->text('fax' ,'Building');
		$form->text('email' ,'Email');
		$form->text('email_support' ,'Delivery Email');
		
		$form->text('new_arrival_days' ,'new arrival days');
		
		$form->text('min_order' ,'Min Order')->default('4.5');
		$form->text('delivery_charges' ,'Delivery Charges')->default('0');

		$form->text('max_palms' ,'Max # of palms')->default('5');
		$form->text('delivery_charges_palms' ,'Delivery Charges For palms')->default('30');

		$form->text('max_sheeps' ,'Max # of Sheeps')->default('5');
		$form->text('delivery_charges_sheeps' ,'Delivery Charges For Sheeps')->default('10');

		$form->text('max_cows' ,'Max # of Cows')->default('1');
		$form->text('delivery_charges_cows' ,'Delivery Charges For Cows')->default('10');


		$form->email('email_sales','email_sales');
        $form->text('framer_title_en' ,'Framer Title en');
        $form->text('framer_title_ar' ,'Framer Title ar');
        $form->textarea('framer_desc_en' ,'Framer Description en');
        $form->textarea('framer_desc_ar' ,'Framer Description ar');
        $form->text('link' ,'Home Framer Link');
        $form->number('default_commission_kwd' ,'Commission kwd ');
        $form->number('default_commission_precentage' ,'Commission precentage ');

		$form->display('created_at', 'Created At');
		$form->display('updated_at', 'Updated At');
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();

        });
		return $form;
	}
}
