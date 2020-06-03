@extends('vendors.layouts.app')
@section('title' , 'Reports')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Reports
                <small>Sales</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Reports</a></li>
                <li class="active">Sales</li>
            </ol>
        </section>
        @if(Session::has('error'))
            <div class="pad margin no-print">
                <div class="callout callout-danger" style="margin-bottom: 0!important;">
                    <h4><i class="fa fa-danger"></i> Error:</h4>
                    {{ Session::get('error') }}
                </div>
            </div>
        @endif
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <div class="pull-right">
                                <div class="btn-group" style="margin-right: 10px" data-toggle="buttons">
                                    <label class="btn btn-sm btn-dropbox filter-btn" title="Filter">
                                        <input type="checkbox"><i class="fa fa-filter"></i><span class="hidden-xs">&nbsp;&nbsp;Filter</span>
                                    </label>
                                </div>
                                </span>
                            </div>

                        </div>
                        <div class="box-header with-border {{ $request->has('search') ? '' : 'hide' }}" id="filter-box">
                            <form action="{{ route('vendor.reports',['inventory'])}}" class="form-horizontal"
                                  pjax-container="" method="get">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-body">
                                            <input type="hidden" name="search" value="1">


                                            <div class="fields-group">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label"> Categories</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group input-group-sm">

                                                            <select name="category" class="form-control dropdown-menu">

                                                                <option value="">All</option>
                                                                 @foreach($categories as $category)
                                                                 <option value="{{ $category->id }}" @if($request->category['0'] == $category->id ) selected @endif >{{ $category->name }}</option>
                                                                     @endforeach
                                                             </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-8">
                                                <div class="btn-group pull-left">
                                                    <button class="btn btn-info submit btn-sm"><i
                                                            class="fa fa-search"></i>&nbsp;&nbsp;Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="box">

                        <div class="box-body table-responsive">
                            <table id="sale_per_day" class="table table-bordered  table-hover">
                                <tbody>
                                <tr>
                                   <th>Product</th>
                                   <th>Quantity</th>
                                    <th>Sold</th>
                                </tr>

                                    @foreach($products as $product)
                                        <tr>
                                        <th>{{ $product->name_en }}</th>
                                        <th>{{ $product->quantity > 0  ?  $product->quantity   : 0 }}</th>
                                        <th>{{ ($product->orderproducts->sum('quantity')) }}</th></tr>
                                        @endforeach



                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection

@section('lower_javascript')


@endsection
