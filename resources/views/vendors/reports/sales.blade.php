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
                            <form action="{{ route('vendor.reports',['sales'])}}" class="form-horizontal"
                                  pjax-container="" method="get">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-body">
                                            <input type="hidden" name="search" value="1">
                                            <div class="fields-group">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label"> From Date</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <input data-date-format="yyyy-mm-dd"
                                                                   class="form-control datepicker"
                                                                   placeholder="From Date" name="from_date"
                                                                   value="{{ $request->has('from_date') ? $request->from_date : '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="fields-group">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label"> To Date</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <input data-date-format="yyyy-mm-dd"
                                                                   class="form-control datepicker" id="to_date"
                                                                   placeholder="To Date" name="to_date"
                                                                   value="{{ $request->has('to_date') ? $request->to_date : '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="fields-group">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label"> Payment</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group input-group-sm">

                                                             <select name="payment_method" class="form-control dropdown-menu">
                                                                 @foreach($paymentmethods as $method)
                                                                 <option value="{{ $method->id }}" @if($request->payment_method == $method->id) selected @endif>{{ $method->title }}</option>
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
                        <div class="box-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th colspan="2">From</th>
                                    <th colspan="2">To</th>


                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <b style="color:#3c8dbc;">Month</b>
                                        <hr>
                                        <b>{{ $request->has('from_date') ? date('F', strtotime($request->from_date)) : '' }}</b>
                                    </td>
                                    <td>
                                        <b style="color:#3c8dbc;">Year</b>
                                        <hr>
                                        <b>{{ $request->has('from_date') ? date('Y', strtotime($request->from_date)) : '' }}</b>
                                    </td>
                                    <td>
                                        <b style="color:#3c8dbc;">Month</b>
                                        <hr>
                                        <b>{{ $request->has('to_date') ? date('F', strtotime($request->to_date)) : '' }}</b>
                                    </td>
                                    <td>
                                        <b style="color:#3c8dbc;">Year</b>
                                        <hr>
                                        <b>{{ $request->has('to_date') ? date('Y', strtotime($request->to_date)) : '' }}</b>
                                    </td>
                                    <td>


                                    </td>
                                    <td>

                                    </td>
                                    <td>


                                    </td>
                                </tr>
                                <tr>

                                    <th>Order ID</th>
                                    <th>Payment</th>
                                    <th>Sub Total</th>
                                    <th>Total</th>

                                    </td>

                                </tr>
                                @php($countAllOrders = 0) @php($grandTotal = 0)
                                @foreach ($orders as $ordersByType)
                                    @php($countAllOrders +=count($ordersByType))
                                 @php($grandTotal += $ordersByType->total)
                                    <tr>

                                        {{--                                    {{ dd(count($ordersByType)) }}--}}
                                        <td>{{ optional($ordersByType->order)->unique_id }}</td>
                                        <td>{{ $ordersByType->order->paymentmethods->title }}</td>
                                        <td   >{{ number_format($ordersByType->sub_total *$ordersByType->quantity , env('NUMBER_FORMAT')) }}</td>
                                        <td >{{ number_format($ordersByType->order->total , env('NUMBER_FORMAT')) }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td>Grand Total ( {{ $countAllOrders }} )</td>
<td></td>
                                    <td colspan="2">{{ number_format($grandTotal,env('NUMBER_FORMAT')) }}</td>
                                </tr>

                                </tbody>
                            </table>

                            <div class="box-footer clearfix">

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>
    </div>
@endsection

@section('lower_javascript')


@endsection
