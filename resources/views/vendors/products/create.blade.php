@extends('vendors.layouts.app') 
@section('title' , 'Offer Create')
@section('custom_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.css" media="all" rel="stylesheet"
          type="text/css" />
    <style type="text/css">
        .main-section {
            margin: 0 auto;
            padding: 20px;
            margin-top: 100px;
            background-color: #fff;
            box-shadow: 0px 0px 20px #c1c1c1;
        }

        .fileinput-remove,
        .fileinput-upload {
            display: none;
        }
    </style>
@endsection
@section('content')

    <div class="content-wrapper">
    <section class="content-header">
        <h1>
            Offer Managment
            {{--  <small>advanced tables</small>  --}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('vendor/products')}}">product Managment</a></li>
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
                <h3 class="box-title">Product</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>                    {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>                    --}}
                </div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{ route('vendor.offer.store')}}">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label for="products" class="col-sm-2 control-label">Products</label>

                            <div class="col-sm-10">
                                <select name="products" class="form-control select2" style="width: 100%;">
                                        <option value="0">Please Select</option>
                                        @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name_en }}</option>
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
                                <input type="number" name="percentage" min="1" max="100" class="form-control" id="percentage" placeholder="Percentage">
                                @if ($errors->has('percentage'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('percentage') }}</strong>
                                </span> @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="percentage" class="col-sm-2 control-label">Fixed</label>
                            <div class="col-sm-10">
                                <input type="number" name="fixed" min="1" max="100" class="form-control" id="fixed" placeholder="Fixed">
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
                                        <input type="radio" name="is_fixed" value="0" class="minimal is_fixed" checked="" style="position: absolute; opacity: 0;">

                                    </div>&nbsp;No&nbsp;&nbsp;
                                </label>
                                <label class="radio-inline">
                                    <div class="" style="position: relative;" aria-checked="false" aria-disabled="false">
                                        <input type="radio" name="is_fixed" value="1" class="minimal is_fixed" style="position: absolute; opacity: 0;">

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
                                    <input type="text" value="{{ old('from_date') }}" name="from_date" class="datepicker form-control pull-right">
                                    @if ($errors->has('from_date'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from_date') }}</strong>
                                    </span> @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="to_date" class="col-sm-2 control-label">To Date</label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" value="{{ old('to_date') }}" name="to_date" class="datepicker form-control pull-right">
                                    @if ($errors->has('to_date'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('to_date') }}</strong>
                                    </span> @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-info pull-right">Send Request</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
 
@section('lower_javascript')
@endsection
