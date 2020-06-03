@extends('layout.app')
@section('content')

@section('content')

<section class="my-profile">
    <div class="container">
       @include('customer.includes.profile_menu')
        <div class="col-md-9 " style="text-align: right;direction: rtl;" >

            <section class="content-header">
                <h1>
                    رقم الفاتورة
                    <small>#{{ $order->unique_id}}</small>
                </h1>
                 
            </section>
   


    {{--
            <div class="pad margin no-print">
                <div class="callout callout-info" style="margin-bottom: 0!important;">
                    <h4><i class="fa fa-info"></i> Note:</h4>
                    This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
                </div>
                </di> --}}


                <section class="invoice">
                    <!-- title row -->
                    <div id="details">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <i class="fa fa-globe"></i> Bastaat. {{-- <small class="pull-right">{{ date('Y-m-d') }}</small>                        --}}
                                <small style=" color:#000000 " class="pull-right">

                                
                                    </small>
                                <br />


                            </h2>

                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                        
        
                                <div class="col-sm-4 invoice-col">
                            {{-- <b>Invoice #007612</b><br> --}}
                            <br>
                            @foreach($order->ordertransactions as $ordertransactions)
                            <b>رقم العملية   : </b>{{ ($ordertransactions->payment_id) }}<br>
                            <b>النتيجة : </b>{{ ($ordertransactions->result) }}<b></b><br>
                            <b>  الرقم المرجعي :</b>{{ $ordertransactions->tran_id }}<br>
                            @endforeach
                        </div>
                        <div class="col-sm-4 invoice-col">
                            {{-- <b>Invoice #007612</b><br> --}}
                            <br>
                            <b>   رقم الطلب : </b>{{ $order->unique_id}}<br>
                            <b> تاريخ الطلب :</b> {{ date('Y-m-d' , strtotime($order->order_date)) }} <br>
                            <b> وقت الطلب : </b>{{ date('H:i:m' , strtotime($order->order_date)) }}<br> {{-- <b>Account:</b> 968-34567 --}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-striped" style="text-align: right">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">رقم المزرعة </th>
                                        <th style="text-align: center;">اسم المنتج</th>
                                        <th style="text-align: center;">الوزن</th>
                                        <th style="text-align: center;">صورة المنتج </th>
                                        <th style="text-align: center;">الكمية</th>
                                        {{--
                                        <th>Is paid</th> --}} {{--
                                        <th>Is confirmed</th> --}}
                                        <th style="text-align: center;">سعر الوحدة</th>
                                        <th style="text-align: center;">الاجمالي</th>

                                    </tr>
                                </thead>
                                <tbody>
                                        @php($sub_total = 0)
                                        @foreach ($order->orderproducts as $orderItem)

                                        @php($sub_total += $orderItem->total )
                                    <tr>
                                    <td style="text-align: center;">{{ $orderItem->product->vendor->code }}</td>
                                        <td style="text-align: center;">{{ $orderItem->product->name_ar }}</td>
                                        <td style="text-align: center;">
                                        {{ $orderItem->extraoption }}
                                        </td>
                                            <td style="text-align: center;"><img
                                    src="{{ asset($orderItem->product->main_image_path) }}"
                                    style="max-height: 150px;"/></td>
                                        <td style="text-align: center;">{{ $orderItem->quantity }}</td>
                                        <td style="text-align: center;">{{ number_format($orderItem->sub_total, env('NUMBER_FORMAT')) }} د ك</td>
                                        <td style="text-align: center;"> {{ number_format($orderItem->total , env('NUMBER_FORMAT')) }} د ك
                                        </td>

                                    </tr>

                                    </td>
                                    <tr></tr>

                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">

                            <p class="lead">طريقة الدفع</p>


                            
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            {{  $order->paymentmethods->name }}
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
                                        <th style="width:50%">الاجمالي : </th>
                                        <td>{{ number_format($sub_total,env('NUMBER_FORMAT')) }} د ك</td>
                                        
                                    </tr>
                                    <tr>
                                    <th>رسوم الشحن :</th>
                                        <td>{{ number_format( $order->delivery_charges,env('NUMBER_FORMAT')) }} د ك</td>
                                        
                                    </tr>
                                    @if($order->discount)
                                    <tr>
                                    <th>الخصم</th>
                                        <td>{{  number_format($order->discount ,env('NUMBER_FORMAT')) }} - د ك</td>
                                        
                                    </tr>
                                    @endif
                                    <tr>
                                    <th>الاجمالي</th>
                                        <td>{{ number_format($sub_total+$order->delivery_charges ,env('NUMBER_FORMAT')) }} د ك</td>
                                       
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
                                    
                                    <tr>
                                        <th>#{{ $key }}</th>

                                        <td>{{ $track->orderstatus->title_ar  }}</td>
                                        <td>{{ $track->created_at  }}</td>
                                    </tr>
                                        <?php $laststatus=$track->orderstatus->id;   ?>
                                
                                @endforeach




                            </table>
                        </div>
                    </div>
                    </div>
                    <div class="row no-print">
                        <div class="col-xs-12">
                            <a href="{{ route('customer.order.print_invoice',$order->id) }}"  target="_blank" class="btn btn-default"><i class="fa fa-print"></i> طباعة</a>

                            {{--  <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                    <i class="fa fa-download"></i> Generate PDF
                </button>  --}}
                        </div>
                    </div>
                </section>
                <div class="clearfix"></div>
            </div>
        </div>
    @include('includes.works');
    <script>
function printDiv() 
{

  var divToPrint=document.getElementById('details');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},10);

}
    </script>

</div>
</section>
@endsection
