<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bastaat | Invoice</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor_assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor_assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('vendor_assets/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor_assets/dist/css/AdminLTE.min.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body >
    <div class="wrapper">
        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <i class="fa fa-globe"></i> Bastaat.
                    </h2>
                </div>
            </div>
            <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">

                        </div>
                        <div class="col-sm-4 invoice-col">
                        
                            @if($order->is_guest==1)
                                <address>
                                  </address>
                            @else
                                <address>
                                  </address>
                            @endif
                            </div>
                <div class="col-sm-4 invoice-col">
                    <b>{{ $order->unique_id}} #رقم الفاتورة </b><br>  
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
                            <th>كود المزرعة</th>
                            <th>اسم المنتج</th>
                                <th>صورة المنتج </th>
                                <th>الكمية</th>
                                <th>سعر الوحدة</th>
                                <th>الاجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                                @php($sub_total = 0)
                                @foreach ($order->orderproducts as $orderItem)
                                 @php($sub_total += $orderItem->price * $orderItem->quantitiy)
                               <tr>
                               <td  >{{ $orderItem->product->vendor->code }}</td>

                               <td>{{ $orderItem->product->name }}  {{ $orderItem->extraoption }}</td>
                                     <td><img
                              src="{{ asset($orderItem->product->main_image_path) }}"
                              style="max-height: 150px;"/></td>
                                   <td>{{ $orderItem->quantity }}</td>
                                   <td>{{ number_format($orderItem->sub_total, env('NUMBER_FORMAT')) }} KD</td>
                                   <td> {{ number_format($orderItem->total  , env('NUMBER_FORMAT')) }} KD
                                   </td>
                               </tr>

                              </td>
                               <tr>


                               </tr>

                               @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                    <div class="col-xs-6">
                        <p class="lead">طريقة الدفع</p>
                        {{--  <img src="{{ asset('vendor_assets2/dist/img/credit/visa.png') }}" alt="Visa">  --}}
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

                                @php($sub_total += $orderItem->sub_total * $orderItem->quantity)
                                @endforeach
                                <tr>
                                     @php 
                            $vendorOrderDetails = $order->vendororderdeliverycharges->first();
                            @endphp
                                <tr>
                                
                                <td>{{ number_format($sub_total,env('NUMBER_FORMAT')) }} د ك</td>
                                <th style="width:50%">: الاجمالي </th>
                            </tr>
                            <tr>
                                <td>{{ number_format( $vendorOrderDetails->delivery_charges,env('NUMBER_FORMAT')) }} د ك</td>
                                <th> :رسوم الشحن</th>
                            </tr>
                                @if($order->discount)
                                <tr>
                                <td>{{  number_format($order->discount ,env('NUMBER_FORMAT')) }} - د ك</td>
                                <th>:الخصم</th>
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
                                
                                <td>{{ number_format($sub_total-0.25-(  ($sub_total * $vendorOrderDetails->commission_percentage / 100)) ,env('NUMBER_FORMAT')) }} د ك</td>
                                <th>الاجمالي</th>
                            </tr>
                            </table>
                        </div>
                    </div>
                </div>
           
        </section>
    </div>
<script>
    
     window.print();
    
</script>
</body>

</html>
