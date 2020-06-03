@extends('vendors.layouts.app')
@section('title' , 'Order Create')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Order
            <small>Create</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('vendor/orders')}}">Orders</a></li>
            <li class="active">Create</li>
        </ol>
    </section>
    @if(Session::has('success'))
    <div class="pad margin no-print">
        <div class="callout callout-success" style="margin-bottom: 0!important;">
            <h4><i class="fa fa-success"></i> Success:</h4>
            {{ Session::get('success') }}
        </div>
    </div>
    @endif
    <section class="content">
        <form action="{{ route('vendor.order.store')}}" method="post">
            @csrf
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Date And Time</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>                        {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>                        --}}
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" value="{{ old('order_date') }}" name="order_date" class="form-control pull-right" id="datepicker">
                                    </div>
                                </div>
                                @if ($errors->has('order_date'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('order_date') }}</strong>
                                </span> @endif
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Time:</label>
                                    <div class="input-group">
                                        <input type="text" value="{{ old('order_time') }}" name="order_time" class="form-control timepicker">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('order_time'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('order_time') }}</strong>
                                </span> @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Customer/Address Information</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>                        {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>                        --}}
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Customer Name:</label>
                                    <input type="text" value="{{ old('customer_name') }}" name="customer_name" class="form-control">                                    @if ($errors->has('customer_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('customer_name') }}</strong>
                                    </span> @endif
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Customer Email:</label>
                                    <input type="email" value="{{ old('customer_email') }}" name="customer_email" class="form-control">                                    @if ($errors->has('customer_email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('customer_email') }}</strong>
                                    </span> @endif
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mobile:</label>
                                    <input type="text" value="{{ old('customer_mobile') }}" name="customer_mobile" class="form-control">                                    @if ($errors->has('customer_mobile'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('customer_mobile') }}</strong>
                                    </span> @endif
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Area:</label>
                                    <select name="address_area" class="form-control select2" style="width: 100%;" required="">
                                  @foreach ($areas as $area)
                                   <option value="{{ $area->id }}">{{ $area->name }}</option>
                                  @endforeach
                                </select> @if ($errors->has('address_area'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('address_area') }}</strong>
                                </span> @endif
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Block:</label>
                                    <input type="text" required="" value="{{ old('address_block') }}" name="address_block" class="form-control">                                    @if ($errors->has('address_block'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address_block') }}</strong>
                                    </span> @endif
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Street:</label>
                                    <input type="text" required="" value="{{ old('address_street') }}" name="address_street" class="form-control">                                    @if ($errors->has('address_street'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address_street') }}</strong>
                                    </span> @endif
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Avenue:</label>
                                    <input type="text" value="{{ old('address_avenue') }}" name="address_avenue" class="form-control">                                    @if ($errors->has('address_avenue'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address_avenue') }}</strong>
                                    </span> @endif
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>House:</label>
                                    <input type="text" required="" value="{{ old('address_house') }}" name="address_house" class="form-control">                                    @if ($errors->has('address_house'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address_house') }}</strong>
                                    </span> @endif
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Floor:</label>
                                    <input type="text" value="{{ old('address_floor') }}" name="address_floor" class="form-control">                                    @if ($errors->has('address_floor'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address_floor') }}</strong>
                                    </span> @endif
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Apartment:</label>
                                    <input type="text" value="{{ old('address_apartment') }}" name="address_apartment" class="form-control">                                    @if ($errors->has('address_apartment'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address_apartment') }}</strong>
                                    </span> @endif
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Location:</label>
                                    <input type="text" value="{{ old('address_loaction') }}" name="address_loaction" class="form-control">                                    @if ($errors->has('address_loaction'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address_loaction') }}</strong>
                                    </span> @endif
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
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>                        {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>                        --}}
                    </div>
                </div>
                <div class="box-body">
                    <div class="row products_select">
                        <div class="repeated hide products_class">
                            <div class="col-md-12 products">
                                <div class="col-md-6">
                                    <a style="display:block;" class="btn_cancel_product">
                                        <i class="fa fa-remove" style="cursor: pointer;color:red;"></i>
                                    </a>
                                    <div class="form-group">
                                        <label>Products:</label>
                                        <select name="products[][product_id]" class="form-control product_select" style="width: 100%;">
                                            <option selected disabled >Please Select</option>
                                            @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ optional($product)->{'name_'.app()->getLocale()} }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="repeated_1 products_class">
                            <div class="col-md-12 products">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Products:</label>
                                        <select name="products[][product_id]" class="form-control product_select" style="width: 100%;">
                                            <option selected disabled >Please Select</option>
                                            @foreach ($products as $product)
                                               <option value="{{ $product->id }}">{{ optional($product)->{'name_'.app()->getLocale()} }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="box-header pull-right">
                      <b class="pull-right">Total </b>
                        <br /><span id="sub_total">0</span> KD
                    </div>
                </div>
                <div class="box-header with-border">
                    <div class="col-md-3">
                        <span class="box-title"><a style="cursor:pointer;" id="add_other_select">Add New Product</a></span>
                    </div>
                </div>
            </div>

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Payment</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>                        {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>                        --}}
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Discount (KWD):</label>
                                    <input type="text" value="{{ old('order_discount') }}" name="order_discount" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Status:</label>
                                    <div class="form-group">
                                        <label>
                                       <input type="radio" value="{{ old('payment_status',0) }}" name="payment_status" class="minimal">
                                            No
                                    </label> &nbsp;&nbsp;
                                        <label>
                                       <input type="radio" value="{{ old('payment_status',1) }}" name="payment_status" class="minimal">
                                        Yes
                                    </label> &nbsp;&nbsp;
                                        {{-- <label>
                                       <input type="radio" value="{{ old('payment_status',2) }}" name="payment_status" class="minimal">
                                            Send Payment Link
                                    </label> --}}
                                    </div>
                                    @if ($errors->has('payment_status'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('payment_status') }}</strong>
                                    </span> @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Delivery Charge:</label>
                                    <input type="text" value="{{ old('order_delivery_charge') }}" name="order_delivery_charge" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group col-xs-6 pull-right">
                                <span class="input-group-btn">
                                     <button type="submit" class="btn btn-primary">Add Offline Order</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>
@endsection
 
@section('lower_javascript')
<script>
    $(document).ready(function(){
        var inc = 2;
        var product_detail = {};
        $('#add_other_select').click(function(){
            var fields = $('.repeated').clone(true,true).attr('class' , 'products_class repeated_' + inc);
            $('.products_select').append(fields).trigger('change');
          //  $('.repeated_' + inc).removeClass('hide');
            inc ++;
        });
        
        $('.product_select').change(function(){
            var obj = $(this);
            var product_route = "{{ route('vendor.order.product_detail_ajax', ':id') }}" ;
           
            id = $(this).val();
            product_route = product_route.replace(':id' , id);

            if(product_detail[id]==undefined) {
                $.get(product_route, function (data, status) {
                    product_detail[id]=String(data.html).replace('position: absolute','');

                    appendAndShowModal(obj, id, product_detail[id]);
                });
            }
            else {
                appendAndShowModal(obj, id, product_detail[id]);
            }
        });

        $('.btn_cancel_product').click(function(){
            $(this).closest('.products_class').remove();
        });

    });
    

</script>
<script>
  function addToCart(is_waiting) {
    products = addOptionToProduct();
    $.ajax({
      method: "post",
      url: "{{ route('vendor.order.add_to_cart') }}",
      data: {
           products : JSON.stringify(products) ,
           _token : $('meta[name="csrf-token"]').attr('content')
        },
      success: function (result) {
        $('.bs-example-modal-lg').modal('hide');
        $('#sub_total').text(result.total);
      },
      error: function () {
      }
    });

  }

  function addOptionToProduct(){
    products = [];
    $('.products_class').not('.hide').each(function() {
            var productId = $(this).find('.product_select').val();

            var Item = {
                product_id: productId
            };

            $(this).find('input, select, textarea').each(function() {
                    var $this = $(this);

                    if (['radio', 'checkbox'].indexOf(String($this.attr('type')).trim()) != -1) {
                        if ($this.prop('checked')) {
                            addToItem(Item, $(this));
                        }
                    } else {
                        addToItem(Item, $(this));
                    }
                }
            );
            products.push(Item);
    });

   return products;
    
}


    function addToItem(Item, $elem) {
        if (String($elem.attr('name')).lastIndexOf('[]') == String($elem.attr('name')).length - 2) {

            if (Item[$elem.attr('name').replace('[]', '')] == undefined) {
                Item[$elem.attr('name').replace('[]', '')] = [];
            }

            Item[$elem.attr('name').replace('[]', '')].push($elem.val());
        } else {
            Item[$elem.attr('name')] = $elem.val();
        }
    }

    function appendAndShowModal(obj, id, html){
        var $modalParent = obj.closest('.products_class'); //('.products_class');
        
        $modalParent.children('.products').find('.product_options_btn').remove();
        $modalParent.children('.products').append(
            '<div class="col-mod-6 product_options_btn">'+ 
                '<a style="cursor:pointer;" onclick="showModal('+id+')">'+
                '<p>Options</p>' +
                '</a>'+
            '</div>'
            );
    
        $modalParent.find('.product_option_modal').remove();
        $modalParent.append($('<div class="product_option_modal" id="product_id_'+id+'">').append(html));

        $('#product_id_'+id+' .modal').modal('show');

    }

    function showModal(id){
        $('#product_id_'+id+' .modal').modal('show');
    }

</script>
@endsection