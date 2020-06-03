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
                <li class="active">Commission</li>
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
                            <form action="{{ route('vendor.reports',['commission'])}}" class="form-horizontal"
                                  pjax-container="" method="get">
                                <div class="box-body">
                                    <div class="row">


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
                                        </div> </div>
                                </div>
                            </form>
                        </div>

                        <div class="box-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Order Total</th>
                                    <th>Order Commission</th>
                                </tr>
                                </thead>
                                <tbody>

                                @php($countAllOrders = 0) @php($grandTotal = 0)
                                @php($totalCommission = 0)
                                @foreach ($vendorcommission as $order)
                                            @php($countAllOrders +=count($order))
                                            <tr>
                                                <td>{{ $order->order->unique_id }}</td>
                                                <td>{{ $order->order->total }}</td>
                                                <td>{{ ($order->order->total * 40 /100) }}</td>
                                            </tr>
                                            @php($grandTotal += $order->order->total)
                                            @php($totalCommission += (($order->order->total * 40 /100)))

                                @endforeach

                                <tr>
                                    <td> Total ( {{ $countAllOrders }} )</td>
                                    <td colspan="1">{{ number_format($grandTotal,env('NUMBER_FORMAT')) }}</td>
                                    <td>{{ number_format($totalCommission,env('NUMBER_FORMAT')) }}</td>
                                </tr>

                                </tbody>
                            </table>

                            <div class="box-footer clearfix">
                                <p>Total Sales After Commission : {{  number_format($grandTotal-$totalCommission,env('NUMBER_FORMAT'))  }}</p>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection

@section('lower_javascript')

<style>
    .select2
    {
        width: 70% !important;
    }
</style>
@endsection
