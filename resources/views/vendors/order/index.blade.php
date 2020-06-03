@extends('vendors.layouts.app') 
@section('title' , 'Order') 
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Orders
            {{--  <small>advanced tables</small>  --}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            {{--  <li><a href="#"></a></li>  --}}
            <li class="active">Orders</li>
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
        <div class="row">
            <div class="col-xs-12">
                {{--  <p class="lead">Amount Due 2/22/2014</p>  --}}
                <div class="table-responsive">
                    <form action="{{ route('vendor.order.index') }}" method="get" >
                        <div class="form-group  ">

                            @csrf
                            <div class="col-sm-4">
                                <label>Status:</label>
                                 <select class="form-control"
                                        style="width: 100%;" name="trackstatus" required

                                        data-placeholder="Input Categories"
                                        aria-hidden="true">

                                    @foreach($orderStatus as $status)

                                        <option
@if($request->trackstatus ==  $status->id) selected @endif
                                            value="{{ $status->id }}">{{ $status->title }}</option>
                                    @endforeach

                                </select>


                            </div>
                            <div class="col-sm-4">

                                <div class="form-group">
                                    <label>Date:</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" value="{{ $request->order_date }}" name="order_date" id="datepicker">
                                    </div>
                                    <!-- /.input group -->
                                </div>

                            </div>
                        </div>

                        <div class="col-sm-4">
<br />

                            <div class="form-group">
                                <label></label>
                        <button type="submit" class="btn btn-default">Filter</button>
                            </div></div>
                    </form>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                    </div>
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <th>Id</th>
                                <th>name</th>

                                
                                <th>status</th>
                                {{--  <th>payment</th>  --}}
                                <th>action</th>
                            </tr>
                                @foreach ($orders as $orders_date => $order )
                                @if($request->get( 'trackstatus' ) && $request->get( 'trackstatus' ) == $order->ordertrack->last()->order_status_id )
                                <tr>
                                    <td>{{ $order->unique_id }}</td>
                                    @if($order->is_guest == 1 )
                                        <td>{{ optional($order->guestusers)->name }}</td>

                                        
                                        <td>{{ $order->ordertrack->last()->orderstatus->title }}</td>
                                        <td>

                                            <a href="{{ route('vendor.order.show' ,[$order->id])}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                        @else
                                    <td>{{ optional($order->user)->name }}</td>

                                    
                                        <td>{{ $order->ordertrack->last()->orderstatus->title }}</td>

                                         <td>
                                        <a href="{{ route('vendor.order.show' ,[$order->id])}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                    </td>
                                        @endif
                                </tr>
                                    @elseif(!$request->get( 'trackstatus' ))
                                <tr>
                                    <td>{{ $order->unique_id }}</td>
                                    @if($order->is_guest == 1 )
                                        <td>{{ optional($order->guestusers)->name }}</td>

                                        
                                        <td>{{ $order->ordertrack->last()->orderstatus->title }}</td>
                                        <td>

                                            <a href="{{ route('vendor.order.show' ,[$order->id])}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    @else
                                        <td>{{ optional($order->user)->name }}</td>

                                         
                                        <td>
                                            @if(null !== ($order->ordertrack->last()))
                                            {{ $order->ordertrack->last()->orderstatus->title }}
                                        @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('vendor.order.show' ,[$order->id])}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                                @endif
                                @endforeach

                            </tbody>

                        </table>
                        {{ $orders->links() }}
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
<script>
    $('#datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
</script>
@endsection
