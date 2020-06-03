@extends('vendors.layouts.app')
@section('title' , 'Edit Order')
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Order
        <small>Edit</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('vendor/orders')}}">Orders</a></li>
        <li class="active">Edit</li>
      </ol>
    </section>
    <section class="content">
      <form action="{{ route('vendor.order.save',[$order->id])}}" method="post">
        <input type="hidden" name="id" id="id" value="{{ $order->id }}">
        @csrf
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Date And Time</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>--}}
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Date <span class="text-danger">*</span> :</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" value="{{ $order->order_date }}" name="order_date"
                             class="form-control pull-right" id="datepicker">
                    </div>
                  </div>
                  @if ($errors->has('order_date'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('order_date') }}</strong>
                    </span>
                  @endif
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Time <span class="text-danger">*</span> :</label>
                    <div class="input-group">
                      <input type="text" value="{{ $order->order_date }}" name="order_time"
                             class="form-control timepicker">
                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                    </div>
                  </div>
                  @if ($errors->has('order_time'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('order_time') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Customer/Address Information</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button> {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>                        --}}
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Customer Name <span class="text-danger">*</span> :</label>
                    <input type="text" value="{{ optional($order->user)->name }}" name="customer_name"
                           class="form-control" required>
                    @if ($errors->has('customer_name'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('customer_name') }}</strong>
                      </span>
                    @endif
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Customer Email <span class="text-danger">*</span> :</label>
                    <input type="email" value="{{ optional($order->user)->email }}" name="customer_email"
                           class="form-control" required>
                    @if ($errors->has('customer_email'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('customer_email') }}</strong>
                      </span>
                    @endif
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Mobile <span class="text-danger">*</span> :</label>
                    <input type="text" value="{{ $userAddress->mobile_no }}" name="customer_mobile" class="form-control" required>
                    @if ($errors->has('customer_mobile'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('customer_mobile') }}</strong>
                      </span>
                    @endif
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Area <span class="text-danger">*</span> :</label>

                    <select name="address_area" class="form-control" style="width: 100%;" required>
                    @foreach ($auth->area as $area)
                      <option value="{{ $area->id }}" {{ ($userAddress->area_id== $area->id )? 'selected=""': '' }}>{{ $area->name }}</option>
                    @endforeach
                    </select>
                    @if ($errors->has('address_area'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('address_area') }}</strong>
                      </span>
                    @endif
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Block <span class="text-danger">*</span> :</label>
                    <input type="text" value="{{ $userAddress->block }}" name="address_block" class="form-control" required>
                    @if ($errors->has('address_block'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('address_block') }}</strong>
                      </span>
                    @endif
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Street <span class="text-danger">*</span> :</label>
                    <input type="text" value="{{ $userAddress->street }}" name="address_street" class="form-control" required>
                    @if ($errors->has('address_street'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('address_street') }}</strong>
                      </span>
                    @endif
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Avenue:</label>
                    <input type="text" value="{{ $userAddress->avenue }}" name="address_avenue" class="form-control">
                    @if ($errors->has('address_avenue'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('address_avenue') }}</strong>
                      </span>
                    @endif
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>building number <span class="text-danger">*</span> :</label>
                    <input type="text" value="{{ $userAddress->bldg_no }}" name="address_house" class="form-control" required>
                    @if ($errors->has('address_house'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('address_house') }}</strong>
                      </span>
                    @endif
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Floor:</label>
                    <input type="text" value="{{ $userAddress->floor }}" name="address_floor" class="form-control">
                    @if ($errors->has('address_floor'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('address_floor') }}</strong>
                      </span>
                    @endif
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Apartment <span class="text-danger">*</span> :</label>
                    <input type="text" value="{{ $userAddress->flat_no }}" name="address_apartment" class="form-control" required>
                    @if ($errors->has('address_apartment'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('address_apartment') }}</strong>
                      </span>
                    @endif
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Location:</label>
                    <input type="text" value="{{ $userAddress->exra_direction }}" name="address_loaction" class="form-control">
                    @if ($errors->has('address_loaction'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('address_loaction') }}</strong>
                      </span>
                    @endif
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Products</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button> {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>                        --}}
            </div>
          </div>
          <div class="box-body">
            <div class="products_select">

              <div id="products_clone" class="hide row product-row">
                <div class="col-md-12">
                  <a class="btn_cancel_product">
                    <i class="fa fa-remove" style="cursor: pointer;color:red;"></i>
                  </a>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <select class="form-control product_select">
                      <option selected disabled>Please Select</option>
                      @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ optional($product)->{'name_'.app()->getLocale()} }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group product-option-btn">
                    <button type="button" class="btn-link">Options</button>
                  </div>
                  <div class="hidden product-options"></div>
                </div>
              </div>

              <div class="products_class col-md-12">
                <div class="form-group">
                  <label>Products:</label>
                </div>
                @foreach($orderProducts as $product)
                  <div class="row product-row-{{ $product->id }}">
                    @if($auth->id == $product->vendor_id && optional($order->orderitems->firstWhere('product_id', $product->id))->is_paid==0)
                    <div class="col-md-12">
                      <a class="btn_cancel_product">
                        <i class="fa fa-remove" style="cursor: pointer;color:red;"></i>
                      </a>
                    </div>
                    @endif
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-control" readonly="">{{ $product->{'name_'.app()->getLocale()} }}</label>
                        <input type="hidden" name="products[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      @if($auth->id == $product->vendor_id && optional($order->orderitems->firstWhere('product_id', $product->id))->is_paid==0)
                      <div class="form-group product-option-btn" data-product-id="{{ $product->id }}">
                        <button type="button" class="btn-link" disabled="">Options</button>
                      </div>
                      <div class="hidden product-options-{{ $product->id }}"></div>
                      @endif
                    </div>
                  </div>
                  @endforeach
              </div>
            </div>
          </div>
          <div class="box-header with-border">
            <div class="col-md-6">
              <span class="box-title"><a style="cursor:pointer;" id="add_other_select">Add New Product</a></span>
            </div>
            <div class="col-md-6">
              <span class="box-title">Total Price: <span class="total-order">{{ $order->total?: "0" }}</span></span>
            </div>
          </div>
        </div>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Payment</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button> {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>                        --}}
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Discount (KWD):</label>
                      <input type="text" value="{{ old('order_discount') }}" name="order_discount" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Payment Status <span class="text-danger">*</span> :</label>
                      <div class="form-group">
                        <label>
                          <input type="radio" value="{{ old('payment_status',0) }}" name="payment_status" class="minimal">
                          No
                        </label> &nbsp;&nbsp;
                        <label>
                          <input type="radio" value="{{ old('payment_status',1) }}" name="payment_status" class="minimal">
                          Yes
                        </label> &nbsp;&nbsp;
                        <label>
                          <input type="radio" value="{{ old('payment_status',2) }}" name="payment_status" class="minimal">
                          Send Payment Link
                        </label>
                      </div>
                      @if ($errors->has('payment_status'))
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('payment_status') }}</strong>
                        </span>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Delivery Charge:</label>
                      <input type="text" value="{{ old('order_delivery_charge') }}" name="order_delivery_charge"
                             class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group col-xs-6">
                      <label>&nbsp;</label>
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary">{{$actionName}} Offline Order</button>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </section>
  </div>
  <div id="products_class_edits"></div>
@endsection

@section('lower_javascript')
  <script src="{{ asset('js/incrementing.js') }}"></script>
  <script>
    var product_detail = {};

    function showModal() {
      var $this=$(this);
      var product_id=$this.parent().data('product-id');
      var product_route = "{{ route('vendor.order.product_order_detail_ajax',[ ':id', ':orderid']) }}";

      if(!product_id){
        return;
      }

      product_route = product_route.replace(':id', product_id);
      product_route = product_route.replace(':orderid', {{ $order->id }} );

      if (product_detail[product_id] == undefined) {

        $this.prop('disabled', true).after('<span>Loading....</span>');

        $.get(product_route, function (data, status) {
          if('success'==status) {
            product_detail[product_id] = 1;

            appendAndShowModal(
              product_id,
              String(data.html)
                .replace('position: absolute;', '')
                .replace('position: absolute', '')
                .replace('id="addBtn', 'data-product-id="'+product_id+'" id="addBtn')
                .replace('addToCart(0)', 'saveProduct(this)')
                .replace('addToCart(1)', 'saveProduct(this)')
                .replace('Add to Cart', 'Save')
            );

            $this.prop('disabled', false).parent().find('span').remove();

            $('#product_id_' + product_id + ' .modal').modal('show');
          }
        });
      }
      else {
        $('#product_id_' + product_id + ' .modal').modal('show');
      }
    }

    $('.products_class').on('click', '.btn_cancel_product', function () {
      var $this = $(this);
      var $totalOrder = $('.total-order');

      $totalOrder.text(parseFloat($totalOrder.text())- parseFloat($this.data('price')));

      $(this).parent().parent().remove();
    })
      .on('click', '.product-option-btn button', showModal);

    function saveProduct(elem){
      var $this=$(elem);
      var $row, $name;
      var product_id=$this.data('product-id');
      var productOptionsContainer = $('.product-options-'+product_id);
      var formArray = $('#product_id_'+product_id).serializeArray();

      productOptionsContainer.empty();

      for(var i = 0; $row = formArray[i]; i++){
        if(String($row['name']).indexOf('[')==-1){
          $name = '['+$row['name']+']';
        }
        else {
          $name = '['+String($row['name']).replace('[','][');
        }

        productOptionsContainer.append(
          $('<input>')
            .attr('type', 'hidden')
            .attr('name', 'products['+product_id+'][product_details]'+$name)
            .val($row['value'])
        );
      }

      $('#product_id_' + product_id + ' .modal').modal('hide');
    }

    $('.product-option-btn')
      .find('button').prop('disabled', false);

    $('#add_other_select').click(function () {
      var $newProduct = $('#products_clone').clone();

      $newProduct.removeClass('hide').removeAttr('id');

      $('.products_class').append($newProduct);
    });

    $('.products_class').on('change', '.product_select', function () {
      var $this = $(this);
      var $error=false;

      $this.closest('div.product-row').find('.product-options').empty();

      $('.products_class [class*="product-row-"]').each(function () {
        var $regexMatch;
        if($regexMatch = String($(this).attr('class')).match(/product-row-(\d+)/)){
          $error = $error || $regexMatch[1]==$this.val();
        }
      });

      if($error){
        $this
          .closest('div.product-row')
            .attr('class', 'row product-row')
          .find('.product-options')
            .attr('class', 'hidden product-options')
          .parent()
          .find('.product-option-btn')
            .data('product-id', '');

        swal("Can't Select!", "You can not select a product you already exist in this order!", "info");

        return;
      }



      var $optionBtn = $this
        .closest('div.product-row')
          .attr('class', 'row product-row product-row-'+$this.val())
        .find('.product-options')
          .attr('class', 'hidden product-options product-options-'+$this.val())
        .parent()
        .find('.product-option-btn')
          .data('product-id', $this.val())
        .find('button');

      showModal.call($optionBtn[0]);
    });

    function appendAndShowModal(id, html) {
      $('#products_class_edits').append($('<form onsubmit="return false;" class="product_option_modal" id="product_id_' + id + '">').append(html));

      incrementingJs();
      $('#products_class_edits #product_id_' + id).find(".selectpicker").chosen();
    }

    $(document).on('click', '[id="addBtnCart"]', function(){
      var $form = $(this).closest('form');
      var $price = $form.find('.pricell');
      var $id=String($form.attr('id')).replace('product_id_', '');
      var $totalOrder = $('.total-order');

      $price = String($price.text()).replace(/[^\d\.]+/g, '');

      var $old = parseFloat($('.product-row-'+$id).find('.btn_cancel_product').data('price'));

      if(isNaN($old)){
        $old =0;
      }

      $('.product-row-'+$id).find('.btn_cancel_product').data('price', $price);

      $totalOrder.text(parseFloat($totalOrder.text())+parseFloat($price)-$old);
    });

  </script>
  <script src="{{ asset('js/chosen.jquery.js') }}"></script>
@endsection

@section('custom_css')
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  <link href="{{ asset('css/chosen.css') }}" rel="stylesheet"/>
  <link href="{{ asset('css/custom_admin.css') }}" rel="stylesheet"/>
@endsection
