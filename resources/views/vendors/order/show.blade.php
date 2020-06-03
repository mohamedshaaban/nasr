@extends('vendors.layouts.app') 
@section('title' , 'Order Details') 
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            رقم الفاتورة
            <small>#{{ $order->unique_id}}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('vendor/orders')}}">Order</a></li>
            <li class="active">{{$order->unique_id}}</li>
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
    @if(Session::has('error'))
    <div class="pad margin no-print">
        <div class="callout callout-danger" style="margin-bottom: 0!important;">
            <h4><i class="fa fa-danger"></i> Error:</h4>
            {{ Session::get('error') }}
        </div>
    </div>
    @endif

    {{--
    <div class="pad margin no-print">
        <div class="callout callout-info" style="margin-bottom: 0!important;">
            <h4><i class="fa fa-info"></i> Note:</h4>
            This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
        </div>
        </di> --}}


        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <i class="fa fa-globe"></i> Bastaat. {{-- <small class="pull-right">{{ date('Y-m-d') }}</small>                        --}}
                        <small class="pull-right">  <b>:عمولة</b></small><br />

                        <small style=" color:#000000 " class="pull-right">

                            @if($order->payment_method == 1)
                            @if(isset($vendorOrderDetails->vendors) )
                            {{ $vendorOrderDetails->vendors->vendorcommissions->first()->precentage    }}% :  {{ $vendorOrderDetails->commission_kd }}د ك
                            
                            @endif
                              
                                @else
                                  {{ $vendorOrderDetails->commission_kd }} د ك
                            @endif
                            </small>
                        <br />


                    </h2>

                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                
                <!--Commented Base user requirement's-->
<!--                    <div class="col-sm-4 invoice-col">
                            From
                            <address>
                                <strong>Festivity.</strong><br>
                                <strong>Address : </strong>{{ $setting->location_en }}<br>
                                <strong>Phone:  </strong>{{ $setting->phone }}<br>
                                <strong>Email: </strong> {{ $setting->email }}
                                </address>
                        </div>-->

                        <div class="col-sm-4 invoice-col">
                    {{-- <b>Invoice #007612</b><br> --}}
                    <br>
                    @foreach($order->ordertransactions as $ordertransactions)
                     {{ ($ordertransactions->payment_id) }}<b>:رقم العملية </b><br>
                     {{ ($ordertransactions->result) }}<b>: النتيجة</b><br>
                     {{ $ordertransactions->tran_id }}<b>: الرقم المرجعي</b><br>
                    @endforeach
                </div>
                <div class="col-sm-4 invoice-col">
                    {{-- <b>Invoice #007612</b><br> --}}
                    <br>
                     {{ $order->unique_id}}<b> : رقم الطلب</b><br>
                    {{ date('Y-m-d' , strtotime($order->order_date)) }}<b>: تاريخ الطلب</b> <br>
                     {{ date('H:i:m' , strtotime($order->order_date)) }}<b>: وقت الطلب </b><br> {{-- <b>Account:</b> 968-34567 --}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>اسم المنتج</th>
                                <th>صورة المنتج </th>
                                <th>الكمية</th>
                                {{--
                                <th>Is paid</th> --}} {{--
                                <th>Is confirmed</th> --}}
                                <th>سعر الوحدة</th>
                                <th>الاجمالي</th>

                            </tr>
                        </thead>
                        <tbody>
                                @php($sub_total = 0)
                                @foreach ($order->orderproducts as $orderItem)
@if($orderItem)
                                 @php($sub_total += $orderItem->total )
                               <tr>

                                   <td>{{ optional($orderItem->product)->name }}
                                   {{ $orderItem->extraoption }}
                                   </td>
                                     <td><img
                              src="{{ asset(optional($orderItem->product)->main_image_path) }}"
                              style="max-height: 150px;"/></td>
                                   <td>{{ $orderItem->quantity }}</td>
                                   <td>{{ number_format($orderItem->sub_total, env('NUMBER_FORMAT')) }} د ك</td>
                                   <td> {{ number_format($orderItem->total , env('NUMBER_FORMAT')) }} د ك
                                   </td>

                               </tr>
@endif
                               </td>
                               <tr></tr>

                               @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
              @php($sub_total = 0) 
                                @foreach ($order->orderproducts as $orderItem )

                                @php($sub_total += $orderItem->sub_total * $orderItem->quantity)
                                @endforeach
            <div class="row">
                <div class="col-xs-6">

                    <p class="lead">طريقة الدفع</p>


                    
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">

                        <img src="{{ asset('uploads/'.$order->paymentmethods->image) }}"/>
                    </p>
                </div>



                <div class="col-xs-6">
                    {{--  <p class="lead">Amount Due 2/22/2014</p>  --}}

                    <div class="table-responsive">
                        <table class="table">
                            @php($sub_total = 0) 
                            @foreach ($order->orderproducts as $orderItem )
                            @php($sub_total += $orderItem->sub_total*$orderItem->quantity)
                            @endforeach
                            <tr>
                                
                                <td>{{ number_format($sub_total,env('NUMBER_FORMAT')) }} د ك</td>
                                <th style="width:50%">الاجمالي : </th>
                            </tr>
                            
                            <tr>
                                <td>{{ number_format( $vendorOrderDetails->delivery_charges,env('NUMBER_FORMAT')) }} د ك</td>
                                <th>رسوم الشحن :</th>
                            </tr>
                            @if($order->discount)
                            <tr>
                                <td>{{  number_format($order->discount ,env('NUMBER_FORMAT')) }} - د ك</td>
                                <th>الخصم</th>
                            </tr>
                            @endif
                            <tr>
                                <td>{{ number_format(0.250 ,env('NUMBER_FORMAT')) }} د ك</td>
                                <th>رسوم كي نت </th>
                                </tr>
                                 <tr>
                                <td>{{ number_format(( ($sub_total * $vendorOrderDetails->commission_percentage / 100)),env('NUMBER_FORMAT')) }} د ك</td>
                                <th>رسوم بسطات </th>
                                </tr>
                            <tr>
                                
                                <td>{{ number_format($sub_total-0.25-( ($sub_total * $vendorOrderDetails->commission_percentage / 100)) ,env('NUMBER_FORMAT')) }} د ك</td>
                                <th>الاجمالي</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                {{--  <p class="lead">Amount Due 2/22/2014</p>  --}}
                <p class="lead">حركة الطلب </p>
                <div class="table-responsive">
                    <table class="table">

                        @foreach ($order->ordertrack as $key=>$track )
                            <?php $laststatus = 0 ; ?>
                            @if($vendor_id ==$track->vendor_id )
                            <tr>
                                <th>#{{ $key }}</th>

                                <td>{{ $track->orderstatus->title_ar  }}</td>
                                <td>{{ $track->created_at  }}</td>
                            </tr>
                                <?php $laststatus=$track->orderstatus->id;   ?>
                            @endif
                        @endforeach




                    </table>
                </div>
            </div>
            <div class="col-xs-6">
                {{--  <p class="lead">Amount Due 2/22/2014</p>  --}}
                 <div class="table-responsive">
                  <form action="{{ route('vendor.order.save') }}" method="post" >
                      <div class="form-group  ">

 @csrf
                          <div class="col-sm-8">

                                <input type="hidden" name="order_id" value="{{ $order->id }}"/>
                              <select class="form-control"
                                      style="width: 100%;" name="trackstatus" required

                                      data-placeholder="Input Categories"
                                      aria-hidden="true">

                                      @foreach($orderStatus as $status)

                                          <option
@if($laststatus == $status->id ) selected @endif
                                          value="{{ $status->id }}">{{ $status->title }}</option>
                                      @endforeach

                              </select>


                          </div>
                      </div>


                      <button type="submit" class="btn btn-default">Update</button>
                  </form>
                </div>
            </div>
            <div class="row no-print">
                <div class="col-xs-12">
                    <a href="{{ route('vendor.order.print_invoice',[$order->unique_id]) }} " target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>

                    {{--  <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </button>  --}}
                </div>
            </div>
        </section>
        <div class="clearfix"></div>
    </div>
@endsection
 
@section('lower_javascript')
@endsection
