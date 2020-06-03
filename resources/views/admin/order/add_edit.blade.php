<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">&nbsp;</h3>
              <div class="box-tools">
                <div class="btn-group pull-right" style="margin-right: 5px">

                </div>
                <div class="btn-group pull-right" style="margin-right: 5px">
                  <button type="button" class="btn btn-sm btn-primary add-btn" title="List"><i
                        class="fa fa-save"></i><span class="hidden-xs">&nbsp;save</span></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <form action="{{ $order->id? route('admin_order.update', [$order->id]): route('admin_order.store') }}"
            id="form-order" class="form-horizontal" method="post">
        @if($order->id)
          <input type="hidden" name="_method" value="put">
        @endif
        @csrf
        <div class="row">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Customer Details</h3>
              </div>

              <div class="box-body">
                <div class="fields-group">
                  <div class="form-group">
                    <label for="customer-add-type" class="col-sm-2 control-label">Add Customer</label>
                    <div class="col-sm-10">
                      <input type="checkbox"
                             @if(!Admin::user()->isAdministrator())
                                 disabled
                                 @endif
                             class="customer-add-type la_checkbox" data-on-label="Add New Customer"
                             data-off-label="Select Exist Customer" {{ old('customer-add-type', 'off') == 'on' ? 'checked' : '' }} />
                      <input type="hidden" class="customer-add-type" name="customer-add-type"
                             value="{{ old('customer-add-type', 'off') }}"/>
                    </div>
                  </div>

                  <div id="select_customer">
                    <div class="form-group ">
                      <label for="customer_id" class="col-sm-2 control-label">Customer</label>
                      <div class="col-sm-10">
                        <label class="control-label error" style="display: none;" for="inputError"><i
                              class="fa fa-times-circle-o"></i> This Field is Required</label>
                        <select class="form-control customer_id" style="width: 100%;" name="customer_id"
                                @if(!Admin::user()->isAdministrator())
                                    disabled
                                @endif
                                id="customer_id">
                          @if($order->user_id)
                            <option value="{{ $order->user_id }}" selected="">{{ $order->customername }}</option>
                          @endif
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">Address</label>
                      <div class="col-sm-10">
                        <input type="checkbox" data-off-label="Add new address" data-on-label="Select exist address"
                               @if(!Admin::user()->isAdministrator())
                                   disabled
                               @endif
                               class="add-address-type la_checkbox"{{ old('add-address-type', ($order->address_id? 'on': 'off')) == 'on' ? 'checked' : '' }} />
                        <input type="hidden" class="add-address-type" name="add-address-type"
                               value="{{ old('add-address-type', 'on') }}"/>
                      </div>
                    </div>
                  </div>

                  <div id="add_customer" style="display: none;">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Name</label>
                      <div class="col-sm-10">
                        <label class="control-label error" style="display: none;" for="inputError"><i
                              class="fa fa-times-circle-o"></i> This Field is Required</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                          <input type="text" id="user-name" name="user-name" value="" class="form-control"
                                 placeholder="Input User Name">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-10">
                        <label class="control-label error" style="display: none;" for="inputError"><i
                              class="fa fa-times-circle-o"></i> This Field is Required</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                          <input type="text" id="user-email" name="user-email" value="" class="form-control"
                                 placeholder="Input User email">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">Phone</label>
                      <div class="col-sm-10">
                        <label class="control-label error" style="display: none;" for="inputError"><i
                              class="fa fa-times-circle-o"></i> This Field is Required</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                          <input type="text" id="user-phone" name="user-phone" value="" class="form-control"
                                 placeholder="Input User Phone">
                        </div>
                      </div>
                    </div>
                  </div>
                    @if(!Admin::user()->isAdministrator())
                    <div id="select_address" style="{{ $order->address_id? '': 'display: none;' }}">
                        <div class="form-group">
                            <label for="address_id" class="col-sm-2 control-label">Customer Phone Number</label>
                            <div class="col-sm-10">
                                 {{ optional($order->userAddress)->phone_no }}

                            </div>
                        </div>
                    </div>
                    <div id="select_address" style="{{ $order->address_id? '': 'display: none;' }}">
                        <div class="form-group">
                            <label for="address_id" class="col-sm-2 control-label">Customer Address</label>
                            <div class="col-sm-10">
                                <div class="col-sm-10">
                                   @php

                      $add = 'الدولة  : '.optional(optional($order->userAddress)->countries)->name_ar.'<br />';

                      $add .= 'المدينة  : '.optional($order->userAddress)->city.'<br />';

                      $add .= 'المحافظة :'.optional($order->userAddress)->province.'<br />';
                      $add .= 'القطعة : '.optional($order->userAddress)->block.'<br />';
                      $add .= 'الشارع :'.optional($order->userAddress)->street.'<br />';
                      $add .= 'الجادة : '.optional($order->userAddress)->avenue.'<br />';
                      $add .= 'رقم المنزل  : '.optional($order->userAddress)->fax.'<br />';
                      $add .= 'الطابق : '.optional($order->userAddress)->floor.'<br />';
                      $add .= 'الشقة : '.optional($order->userAddress)->flat.' ';


                      echo trim($add, ',');
                      @endphp

                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                  <div id="select_address" style="{{ $order->address_id? '': 'display: none;' }}">
                    <div class="form-group">
                      <label for="address_id" class="col-sm-2 control-label">Select Address</label>
                      <div class="col-sm-10">
                        <label class="control-label error" style="display: none;" for="inputError"><i
                              class="fa fa-times-circle-o"></i> This Field is Required</label>
                        <input type="hidden" name="address_id"/>
                        <select class="form-control address_id" style="width: 100%;" name="address_id"
                                @if(!Admin::user()->isAdministrator())
                                    disabled
                                @endif
                                id="address_id">
                          @if($order->user_id)
                            <option value="{{ $order->address_id }}"
                                    selected="">{{ optional($order->userAddress)->first_name }}</option>
                          @endif
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div id="add_address" style="{{ $order->address_id? 'display: none;': '' }}">
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Add Address</h3>
                </div>
                <div class="box-body">
                  <div class="fields-group">
                    <div class="col-sm-6">



                      <div class="form-group ">
                        <label for="first_address" class="col-sm-3 control-label">First Name</label>
                        <div class="col-sm-9">
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" id="first_name" name="first_name" value="" class="form-control"
                                   placeholder="Input First name">
                          </div>
                        </div>
                      </div>
                    </div>
                      <div class="col-sm-6">
                      <div class="form-group ">
                        <label for="second_address" class="col-sm-3 control-label">Second Name</label>
                        <div class="col-sm-9">
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" id="second_name" name="second_name" value="" class="form-control"
                                   placeholder="Input Second Address">
                          </div>
                        </div>
                      </div>
                      </div>
                      <div class="col-sm-6">
                      <div class="form-group ">
                        <label for="mobile_no" class="col-sm-3 control-label">Mobile Number</label>
                        <div class="col-sm-9">
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" id="mobile_no" name="mobile_no" value="" class="form-control"
                                   placeholder="Input Mobile Number">
                          </div>
                        </div>
                      </div>
                      </div> <div class="col-sm-6">
                      <div class="form-group ">
                        <label for="phone_no" class="col-sm-3 control-label">Phone Number</label>
                        <div class="col-sm-9">
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" id="phone_no" name="phone_no" value="" class="form-control"
                                   placeholder="Input Phone Number">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <div class="col-sm-6">
                        <div class="form-group ">
                            <label for="governorate_id" class="col-sm-3 control-label">Governorate</label>
                            <div class="col-sm-9">
                                <label class="control-label error" style="display: none;" for="inputError"><i
                                        class="fa fa-times-circle-o"></i> This Field is Required</label>
                                <select class="form-control governorate_id" style="width: 100%;" name="governorate_id"
                                        id="governorate_id">
                                    @foreach($governorates as $governorate)
                                        <option value="{{ $governorate->id }}">{{ $governorate->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ">
                            <label for="area_id" class="col-sm-3 control-label">Area</label>
                            <div class="col-sm-9">
                                <label class="control-label error" style="display: none;" for="inputError"><i
                                        class="fa fa-times-circle-o"></i> This Field is Required</label>
                                <select class="form-control area_id" style="width: 100%;" name="area_id"
                                        id="area_id">
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">

                            <div class="form-group ">
                                <label for="first_name" class="col-sm-3 control-label">City</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="city" name="city" value="" class="form-control"
                                               placeholder="Input City">
                                    </div>
                                </div>
                            </div>


                    </div>

                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="first_name" class="col-sm-3 control-label">block</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="block" name="block" value="" class="form-control"
                                               placeholder="Input block">
                                    </div>
                                </div>
                            </div>

                    </div>

                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="first_name" class="col-sm-3 control-label">street</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="street" name="street" value="" class="form-control"
                                               placeholder="Input street">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="first_name" class="col-sm-3 control-label">avenue</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="avenue" name="avenue" value="" class="form-control"
                                               placeholder="Input avenue">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="first_name" class="col-sm-3 control-label">floor</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="floor" name="floor" value="" class="form-control"
                                               placeholder="Input floor">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="first_name" class="col-sm-3 control-label">flat</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="flat" name="flat" value="" class="form-control"
                                               placeholder="Input flat">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="first_name" class="col-sm-3 control-label">fax</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="fax" name="fax" value="" class="form-control"
                                               placeholder="Input fax">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="first_name" class="col-sm-3 control-label">company</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="company" name="company" value="" class="form-control"
                                               placeholder="Input fax">
                                    </div>
                                </div>
                            </div>

                        </div>                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="first_name" class="col-sm-3 control-label">zip_code</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="zip_code" name="zip_code" value="" class="form-control"
                                               placeholder="Input fax">
                                    </div>
                                </div>
                            </div>

                        </div> <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="first_name" class="col-sm-3 control-label">extra_direction</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="extra_direction" name="extra_direction" value="" class="form-control"
                                               placeholder="Input fax">
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                  </div>
                </div>
              </div>
            </div>




        <div>
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Order Status</h3>
                </div>
                <div class="box-body">
                  <div class="fields-group">
                    <div class="form-group">
                      <label for="order_status" class="col-sm-3 control-label">Order Status</label>
                      <div class="col-sm-6">
                        <label class="control-label error" style="display: none;" for="inputError"><i
                              class="fa fa-times-circle-o"></i> This Field is Required</label>
                        <select name="order_status" id="order_status" class="form-control">
                          @foreach($order_status as $status)
                            <option
                                value="{{ $status->id }}" {!! $status->id==old('order_status', $order->status_id)? 'selected=""': '' !!}>{{ $status->title_en }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <hr>
                    </div>
                  </div>
                  @foreach($statusHis as $statusRow)
                    <div class="row">
                      <div class="col-md-6 col-md-offset-3"><label>{{ optional($statusRow->orderstatus)->title_en }}</label> <span class="pull-right">{{ $statusRow->created_at->diffForHumans() }}</span></div>
                    </div>

                  @endforeach
                </div>
              </div>
            </div>
          </div>
@if(Admin::user()->isAdministrator())
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Order Confirmation</h3>
                </div>
                <div class="box-body">
                  <div class="row">

                    <div class="col-md-6">
                      <div class="fields-group">
                        <div class="form-group">

                          <div class="col-sm-8 col-md-offset-4">
                            <label><input type="checkbox" value="1"
                                          @if(!Admin::user()->isAdministrator())
                                              disabled
                                              @endif
                                          name="is_paid" {{$order->is_paid==1? 'checked="checked"': '' }}> Is Confirmed</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
@endif
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Products</h3>
                  <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">

                    </div>
                  </div>
                </div>

                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover" id="product_tab">
                   <thead>
                    <tr>
                      <th class="text-center" style="width: 100px">رقم المنتج</th>
                      <th class="text-center" style="width: 200px">الصورة </th>

                        <th style="width: 200px">اسم المنتج</th>
                      <th style="width: 200px">الوزن</th>
                      <th style="width: 200px"> </th>
                      <th class="text-center" style="width: 50px">الكمية</th>

                    </tr>
                    </thead>
                    <tbody>
                    @php
                      $subTotal = 0;
                    @endphp
                    @php
                    $products = $order->orderProducts->groupBy('vendor_id');

                    @endphp
                    @foreach($products as $key=>$vendors)
                        @php
                            $vendor = \App\Models\Vendors::find($key);

                        @endphp
                    @foreach($vendors as $key=>$orderProduct)
                      <tr>
                        <td class="text-center data-input" data-name="product_id"
                            data-value="{{ $orderProduct->product_id }}">{{ $orderProduct->product_id }}</td>
                        <td class="text-center">
                          <img src="{!! asset($orderProduct->product->main_image_path) !!}"
                               style="max-height: 150px;"/>
                        </td>
                        <td>{{ $orderProduct->product->name_en }}</td>
                        <td>
                        {{ $orderProduct->extraoption }}
                        </td>
                        @php
                          $optionsArr=[];
                        @endphp
                        @if($orderProduct->orderProductOption)
                          @foreach($orderProduct->orderProductOption as $productOption)
                          @php
                            $optionsArr[]=[
                              'option' => $productOption->option->id,
                              "value"  => $productOption->optionValue->id
                            ];
                          @endphp
                        @endforeach
                          @endif
                        <td class="data-input" data-name="option_id" data-value="{{ json_encode($optionsArr) }}">
                            @if($orderProduct->orderProductOption)
                          @foreach($orderProduct->orderProductOption as $productOption)
                            {{ $productOption->option->name_en }}: {{ $productOption->optionValue->value_en }}<br>
                          @endforeach
                            @endif
                        </td>

                        <td class="text-center data-input" data-name="quantity"
                            data-value="{{ $orderProduct->quantity }}">{{ $orderProduct->quantity }}</td>



                      </tr>
                      @php
                        $subTotal += $orderProduct->sub_total;
                      @endphp
                    @endforeach
                    @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </form>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">&nbsp;</h3>
              <div class="box-tools">

                  <div class="btn-group pull-right" style="margin-right: 5px">
                  <button type="button" class="btn btn-sm btn-primary add-btn" title="List"><i
                        class="fa fa-save"></i><span class="hidden-xs">&nbsp;save</span></button>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>

<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Product</h4>
      </div>
      <div class="modal-body">
        <div class="form-horizontal">
          <div class="fields-group">

            <div class="form-group ">

              <label for="vendor_id" class="col-sm-2 control-label">Select Vendor</label>

              <div class="col-sm-10">
                <label class="control-label error" style="display: none;" for="inputError"><i
                      class="fa fa-times-circle-o"></i> Select Vendor</label>
                <input type="hidden" name="vendor_id"/>
                <select class="form-control" style="width: 100%;" name="vendor_id"
                        id="vendor_id">
                    @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                </select>
              </div>
            </div>


              <div class="form-group ">

              <label for="product_id" class="col-sm-2 control-label">Product</label>

              <div class="col-sm-10">
                <label class="control-label error" style="display: none;" for="inputError"><i
                      class="fa fa-times-circle-o"></i> Select Product</label>

                <select class="form-control product_id" style="width: 100%;" name="product_id"
                        id="product_id"></select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Price</label>
              <div class="col-sm-10">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                  <input type="text" id="price_input" value="" readonly="" class="form-control">
                </div>
              </div>
            </div>


            <div class="form-group">
              <label class="col-sm-2 control-label">Quantity</label>
              <div class="col-sm-10">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                  <input type="text" id="quantity_input" value="1" class="form-control number">
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addProduct">Add Product</button>
      </div>
    </div>
  </div>
</div>

<script>

  function load() {
    $('.la_checkbox')
      .not('.initialize')
      .addClass('initialize')
      .each(function () {
        $(this).bootstrapSwitch({
          size: 'small',
          onText: $(this).data('on-label'),
          offText: $(this).data('off-label'),
          onColor: 'primary',
          offColor: 'default',
          onSwitchChange: function (event, state) {
            $(event.target).closest('.bootstrap-switch').next().val(state ? 'on' : 'off').change();
          }
        });
      });

    $('.customer-add-type').change(function () {
      var val = $(this).val() == 'on';
      $('#select_customer').toggle(!val);
      $('#add_customer').toggle(val);
      $('#select_address').toggle(!val);
      $('#add_address').toggle(val);
      $('.add-address-type.la_checkbox').bootstrapSwitch('state', false);

    });

    $('.add-address-type').change(function () {
      var val = $(this).val() == 'on';
      $('#select_address').toggle(val);
      $('#add_address').toggle(!val);
    });

      $(".customer_id").select2({
          ajax: {
              url: "{!! route('ajax.customer') !!}",
              dataType: 'json',
              delay: 250,
              data: function (params) {
                  return {
                      q: params.term,
                      page: params.page
                  };
              },
              processResults: function (data, params) {
                  params.page = params.page || 1;

                  return {
                      results: $.map(data.data, function (d) {
                          d.id = d.id;
                          d.text = d.name;
                          return d;
                      }),
                      pagination: {
                          more: data.next_page_url
                      }
                  };
              },
              cache: true
          },
          'allowClear': true,
          'placeholder': 'Select Customer',
          'minimumInputLength': 1,
          escapeMarkup: function (markup) {
              return markup;
          }
      })
          .on('select2:select', function (e) {
              $('#address_id').empty().append(function () {
                  var $row, result = [];

                  if (e.params.data.user_address) {
                      for (var i = 0; $row = e.params.data.user_address[i]; i++) {
                          result.push($('<option>').val($row.id).text($row.first_name));
                      }
                  }

                  return result;
              })
          })
          .on('change', function () {
              if ($(this).val() == null) {
                  $('#address_id').empty();
              }
          });
      $("#vendor_id").select2({
          ajax: {
              url: "{!! route('ajax.vendor') !!}",
              dataType: 'json',
              delay: 250,
              data: function (params) {
                  return {
                      q: params.term,
                      page: params.page
                  };
              },
              processResults: function (data, params) {
                  params.page = params.page || 1;

                  return {
                      results: $.map(data.data, function (d) {
                          d.id = d.id;
                          d.text = d.name;
                          return d;
                      }),
                      pagination: {
                          more: data.next_page_url
                      }
                  };
              },
              cache: true
          },
          'allowClear': true,
          'placeholder': 'Select Product',
          'minimumInputLength': 1,
          escapeMarkup: function (markup) {
              return markup;
          }
      })
      .on('select2:select', function (e) {

          $.ajax({url: "{!! route('ajax.product') !!}",data:{'vendor_id':e.params.data.id}, success: function(result){
                  $('#product_id').empty();
                  var option = new Option('Please Select', '');

                  $("#product_id").append(option);
                  $.each(result, function(index, value){
                  var option = new Option(value.name_en, value.id);

                  $("#product_id").append(option);
              });
              }});
  });

$('#product_id').on('change', function() {
    // alert(e.params.data.id);
    $.ajax({
        method: "get",
        url: "{!! route('ajax.product.price') !!}",
        data: {'product_id':this.value },

        success: function(result) {
            result = JSON.parse(result);
         $('#price_input').val(result.price);
            $('#addProduct').data('product', result.product);


        }

    });
});

    $('.number').keypress(function (e) {
      var key = e.which || e.keyCode;
      var char = String.fromCharCode(key);
      var allowChar = '0123456789';
      if ($(this).is('.decimal')) {
        allowChar += '.';
      }

      if (allowChar.indexOf(char) == -1) {
        return false;
      }

      if (String($(this).val()).indexOf('.') > -1 && char == '.') {
        return false;
      }
    });

    deliveryMethod = function () {
      var $option = $(this).find('option:selected');
      var $total = $option.data('price');

      if ($option.data('free') == 1) {
        $total = 0;
        $('#product_tab').find('tbody td.totals').each(function () {
          $total += parseFloat($(this).text());
        });

      }

      $('#delivery_charge').text($option.data('price'));
    };

    $('#delivery_method')
      .change(deliveryMethod)
      .change()
      .select2();

    $('#myModal').on('show.bs.modal', function () {
      $('#product_id').val(null).change();
      $('#price_input').val(0);
      $('#quantity_input').val(1);

    });

    $('#addProduct').click(function () {

      $('#myModal').find('.has-error').removeClass('has-error').find('.error').hide();

      if ($('#product_id').val() == null) {
        $('#product_id').closest('.form-group').addClass('has-error').find('.error').show();
      }

      $('#product_tab').find('tbody').append($('<tr>').append(function () {
        var result = [];
        var $product = $('#addProduct').data('product');

        var option = $('.option_id_input:checked');
        var quantity = ($('#quantity_input').val()) || 1;

        result.push($('<td class="text-center data-input">').data('name', 'product_id').data('value', $product.id).text($product.id));
        result.push($('<td class="text-center">').append($('<img style="max-height: 150px;">').attr('src', $product.main_image_path)));
        result.push($('<td>').text($product.name_en));


        if (option.length > 0) {

          var $opValues = [];
          var $opName = [];
          option.each(function () {
            $opValues.push({'option': $(this).data('option_id'), 'value': $(this).val()});
            $opName.push($(this).data('name') + ": " + $(this).data('value'));
          });


          result.push(
            $('<td class="text-center data-input">')
              .data('name', 'option_id')
              .data('value', $opValues)
              .html($opName.join('<br />'))
          );
        }
        else {
          result.push($('<td>'));
        }

        result.push(
          $('<td class="text-center data-input">')
            .data('name', 'quantity')
            .data('value', quantity)
            .text(quantity)
        );
        result.push(
          $('<td class="text-center data-input">')
            .data('name', 'price')
            .data('value', $product.price)
            .text($product.price));
        result.push($('<td class="text-center totals">').text($product.price * quantity));
        result.push($('<td class="text-center">')
          .append('<button type="button" class="delete-product btn-link"><span aria-hidden="true">&times;</span></button>'));

        return result;

      }));

      $('#delivery_method').change();

      $('#myModal').modal('hide');
    });

    $('#form-order').on('click', '.delete-product', function () {
      $(this).closest('tr').remove();
    });

    $('.add-btn').click(function () {

      var formAction = $('#form-order').attr('action');
      var formArr = $('#form-order').serializeArray();
      var $error = false;

      $('#form-order').find('.has-error').removeClass('has-error').find('.error').hide();

      if ($('[name="customer-add-type"]').val() == 'off') {
        if ($('#customer_id').val() == null) {
          $error = true;
          $('#customer_id').closest('.form-group').addClass('has-error').find('.error').show();
        }

        if ($('[name="add-address-type"]').val() == 'on') {
          if ($('#address_id').val() == null) {
            $error = true;
            $('#address_id').closest('.form-group').addClass('has-error').find('.error').show();
          }
        }
        else {
          // if ($('#address_id').val() == null) {
          //   $error=true;
          //   $('#address_id').closest('.form-group').addClass('has-error').find('.error').show();
          // }



          if ($('#governorate_id').val() == null) {
            $error = true;
            $('#governorate_id').closest('.form-group').addClass('has-error').find('.error').show();
          }
        }
      }
      else {
        if (String($('#user-name').val()).trim() == '') {
          $error = true;
          $('#user-name').closest('.form-group').addClass('has-error').find('.error').show();
        }
        if (String($('#user-email').val()).trim() == '') {
          $error = true;
          $('#user-email').closest('.form-group').addClass('has-error').find('.error').show();
        }
        if (String($('#user-phone').val()).trim() == '') {
          $error = true;
          $('#user-phone').closest('.form-group').addClass('has-error').find('.error').show();
        }

        if (String($('#address_type').val()).trim() == '') {
          $error = true;
          $('#address_type').closest('.form-group').addClass('has-error').find('.error').show();
        }
        if ($('#governorate_id').val() == null) {
          $error = true;
          $('#governorate_id').closest('.form-group').addClass('has-error').find('.error').show();
        }
      }



      if ($('#product_tab').find('td.data-input').length == 0) {
        alert('you have to add products');
        $error = true;
      }

      if ($error) {
        return;
      }

      $('#product_tab').find('tbody tr').each(function (index) {

        $(this).find('.data-input').each(function () {

            formArr.push({
              name: 'product[' + index + '][' + $(this).data('name') + ']',
              value: $(this).data('value')
            });

        });
      });

      $.post(formAction, formArr)
        .success(function (d) {
          if (d.status) {
            location.href = '{{ route('admin_order.index') }}';
            return;
          }

          alert(d.msg);
        });

    });

  }


  if (document.readyState === 'complete') {
    load();
  }
  else {
    $(document).ready(load);
  }

</script>
