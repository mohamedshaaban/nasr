@extends('vendors.layouts.app') 
@section('title' , 'Offer Create') 
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Offer Managment
            {{--  <small>advanced tables</small>  --}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('vendor/products/offers/managment')}}">Offer Managment</a></li>
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
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Offer</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>                    {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>                    --}}
                </div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="post" action="{{ route('vendor.offer.store' , [$offer->id])}}">
<input type="hidden" name="offerId" value="{{ $offer->id }}" />
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label for="products" class="col-sm-2 control-label">Products</label>

                            <div class="col-sm-10">
                                <select name="products" class="form-control select2" style="width: 100%;" onchange="getProPrice(this.value)">
                                        <option value="0">Please Select</option>
                                        @foreach ($products as $product)
                                        <option value="{{ $product->id }}" @if($product->id == $offer->product_id) selected @endif>{{ $product->name_en }}</option>
                                        @endforeach
                                </select>
                                @if ($errors->has('products'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('products') }}</strong>
                                </span> @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="percentage" class="col-sm-2 control-label">Percentage</label>
                            <div class="col-sm-10">
                                <input type="number" name="percentage" min="1" max="100" value="{{ $offer->percentage }}" class="form-control" id="percentage" placeholder="Percentage">
                                @if ($errors->has('percentage'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('percentage') }}</strong>
                                </span> @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="percentage" class="col-sm-2 control-label">Fixed</label>
                            <div class="col-sm-10">
                                <input type="number" name="fixed" min="1" max="100" value="{{ $offer->fixed }}" class="form-control" id="fixed" placeholder="Fixed">
                                @if ($errors->has('fixed'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('fixed') }}</strong>
                                </span> @endif
                            </div>
                        </div>
                        <div class="form-group  ">

                            <label for="is_fixed" class="col-sm-2  control-label">Is fixed</label>

                            <div class="col-sm-8">


                                <label class="radio-inline">
                                    <div class="checked" style="position: relative;" aria-checked="false" aria-disabled="false">
                                        <input type="radio" name="is_fixed" value="0" class="minimal is_fixed" @if($offer->is_fixed == 0)  checked @endif style="position: absolute; opacity: 0;">

                                    </div>&nbsp;No&nbsp;&nbsp;
                                </label>
                                <label class="radio-inline">
                                    <div class="" style="position: relative;" aria-checked="false" aria-disabled="false">
                                        <input type="radio" name="is_fixed" value="1" class="minimal is_fixed" @if($offer->is_fixed == 1)  checked @endif style="position: absolute; opacity: 0;">

                                    </div>&nbsp;Yes&nbsp;&nbsp;
                                </label>


                            </div>
                        </div>
                        <div class="form-group">
                            <label for="from_date" class="col-sm-2 control-label">From Date</label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    @php
                                    $from = date('m/d/Y', strtotime($offer->from));
                                    $to = date('m/d/Y', strtotime($offer->to));

                                    @endphp
                                    <input type="text" value="{{ $from }}" name="from_date" class="datepicker form-control pull-right">
                                    @if ($errors->has('from_date'))

                                </div>
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from_date') }}</strong>
                                    </span> @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="to_date" class="col-sm-2 control-label">To Date</label>

                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" value="{{ $to }}"  name="to_date" class="datepicker form-control pull-right">

                                </div>
                                @if ($errors->has('to_date'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('to_date') }}</strong>
                                    </span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-info pull-right">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection
 
@section('lower_javascript')
    <script>
        function getProPrice(ProdId)
        {

            url = '/vendor/getProductPrice';

            $.ajax({
                type : 'GET',
                url: url + '/' + ProdId,
                success : function(data){
                    $("#fixed").attr({
                        "max" : parseInt(data),        // substitute your own

                    });
                }
            });
        }
    </script>
@endsection
