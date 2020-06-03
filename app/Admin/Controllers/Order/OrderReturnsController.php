<?php

namespace App\Admin\Controllers\Order;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\OrderReturnItems;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Carbon\Carbon;
use Encore\Admin\Form\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OrderReturnsController extends Controller
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
        $grid = new Grid(new OrderReturnItems());



        $grid->id('ID')->sortable();
        $grid->quantity('quantity');
        $grid->order()->unique_id('unique_id');
        $grid->product()->name_en('name_en');


        $grid->created_at('Created at');
        $grid->updated_at('Updated at');
        $grid->actions(function ($actions) {
            $actions->disableView();
//            $actions->disableCreation();

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
        $show = new Show(OrderReturnItems::findOrFail($id));

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
        $form = new Form(new OrderReturnItems);

        $form->display('id', 'ID');
        $form->text('quantity', 'Quantity ');
        $form->radio('status', 'Status')->options(['0'=>'Pending'
        ,'1'=>'Confirmed'
        ]);

        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();

        });

        return $form;
    }
    public function update($id, Request $request) {
        $item = OrderReturnItems::findOrFail($id);

        if($request->status ==1 ) {


            $this->updateQty($request, $item->product->sku, $item->product->code, $item->product->quantity + $item->quantity);
        }
        $item->status = $request->status ;
        $item->update();
        return Redirect::back();

    }

    public function createToke()
    {
        $ch = curl_init();
        $fields = [
            'email'=>'info@masdr.me',
            'password'=>'Masdr2030'
        ];
        $data_string = json_encode($fields);
        curl_setopt($ch, CURLOPT_URL,"https://app.skuvault.com/api/gettokens");
//        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

// Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = (curl_exec($ch));
        $server_output_arr = json_decode($server_output, true);
        $TenantToken =$server_output_arr['TenantToken'];
        $UserToken =$server_output_arr['UserToken'];



        //////////////////

        $ch = curl_init();
        $fields_ware = [
            'TenantToken' => $TenantToken,
            'UserToken' => $UserToken
        ];
        $data_string_ware = json_encode($fields_ware);
        curl_setopt($ch, CURLOPT_URL, "https://app.skuvault.com/api/inventory/getLocations");
//        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string_ware);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output_ware = (curl_exec($ch));
        $server_output_ware_arr = json_decode($server_output_ware, true);
        $server_output_ware_arr = $server_output_ware_arr;
        ///
        ///

        return ['TenantToken'=>$TenantToken,'UserToken'=>$UserToken,'locationCode'=>$server_output_ware_arr['Items'][0]['LocationCode']];
        curl_close ($ch);
    }
    public function updateQty(Request $request , $sku,$code,$qty)
    {
         $TenantToken = $this->createToke()['TenantToken'];
        $UserToken = $this->createToke()['UserToken'];
        $locationCode = $this->createToke()['locationCode'];

        //////////////////

        $ch = curl_init();
        $fields_ware = [
            "PageNumber"=>0,
            'TenantToken' => $TenantToken,
            'UserToken' => $UserToken
        ];
        $data_string_ware = json_encode($fields_ware);
        curl_setopt($ch, CURLOPT_URL, "https://app.skuvault.com/api/inventory/getWarehouses");
//        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string_ware);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output_ware = (curl_exec($ch));
        $server_output_ware_arr = json_decode($server_output_ware, true);
        $server_output_ware_arr = $server_output_ware_arr['Warehouses'][0];
        ///


        $ch = curl_init();
        $fields = [
            "Sku"=>$sku,
            "Code"=>$code,
            "WarehouseId"=>$server_output_ware_arr['Id'],
            "LocationCode"=>"01",
            "Quantity"=>$qty,
            'TenantToken' => $TenantToken,
            'UserToken' => $UserToken
        ];
        $data_string = json_encode($fields);
        curl_setopt($ch, CURLOPT_URL, "https://app.skuvault.com/api/inventory/setItemQuantity");
//        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

// Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = (curl_exec($ch));
        $server_output_arr = json_decode($server_output, true);
        return ($server_output_arr);
    }

}
