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

<body id="masterContent">
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
                           <table style="width:100%" width="100%">
                   <tr>
                       <td>
                           <div   >
                        
                            @if($order->is_guest==1)
                            
                                
                                <table>
                                    <tr>
                                        <td>
                                            {{ $order->guestusers->name }}
                                        </td>
                                        <td>
                                           <b> :الي </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional(optional($order->userAddress)->countries)->name_ar }}</td>
                                        <td><b>: الدولة </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->city }}</td>
                                        <td style="text-align: right;"><b>: مدينة </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->province }}</td>
                                        <td style="text-align: right;"><b>: مقاطعة </b></td>
                                    </tr>
                                     <tr>
                                        <td>{{ optional($order->userAddress->area)->name_ar }}</td>
                                        <td style="text-align: right;"><b>: منطقة </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->block }}</td>
                                        <td style="text-align: right;"><b>: بلوك </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->street }}</td>
                                        <td style="text-align: right;"><b>: الشارع </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->avenue }}</td>
                                        <td style="text-align: right;"><b>: الجادة </b></td>
                                    </tr>
                                     <tr>
                                        <td>{{ optional($order->userAddress)->fax }}</td>
                                        <td style="text-align: right;"><b>: رقم المنزل </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->floor }} </td>
                                        <td style="text-align: right;"><b>: الطابق </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->flat }}</td>
                                        <td style="text-align: right;"><b>: شقة </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->phone_no }}</td>
                                        <td style="text-align: right;"><b>: الهاتف </b></td>
                                    </tr>
                                </table>
                                
                            @else
                           
                            <table>
                                <tr>
                                        <td>
                                            {{ $order->user->name }}
                                        </td>
                                        <td>
<b> :الي </b>                                        
</td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional(optional($order->userAddress)->countries)->name_ar }}</td>
                                        <td style="text-align: right;"><b>: الدولة </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->city }}</td>
                                        <td style="text-align: right;"><b>: مدينة </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->province }}</td>
                                        <td style="text-align: right;"><b>: مقاطعة </b></td>
                                    </tr>
                                     <tr>
                                        <td>{{ optional($order->userAddress->area)->name_ar }}</td>
                                        <td style="text-align: right;"><b>: منطقة </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->block }}</td>
                                        <td style="text-align: right;"><b>: بلوك </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->street }}</td>
                                        <td style="text-align: right;"><b>: الشارع </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->avenue }}</td>
                                        <td style="text-align: right;"><b>: الجادة </b></td>
                                    </tr>
                                     <tr>
                                        <td>{{ optional($order->userAddress)->fax }}</td>
                                        <td style="text-align: right;"><b>: رقم المنزل </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->floor }} </td>
                                        <td style="text-align: right;"><b>: الطابق </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->flat }}</td>
                                        <td style="text-align: right;"><b>: شقة </b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ optional($order->userAddress)->phone_no }}</td>
                                        <td style="text-align: right;"><b>: الهاتف </b></td>
                                    </tr>
                                </table>
                            @endif
                            </div>
                       </td>
                       <td>
                          
                       </td>
                       <td>
                           <div  >
                    <b>{{ $order->unique_id}} #رقم الفاتورة </b><br>  
                    <br>
                    {{ $order->unique_id}}<b> : رقم الطلب</b><br>
                    {{ date('Y-m-d' , strtotime($order->order_date)) }}<b>: تاريخ الطلب</b> <br>
                     {{ date('H:i:m' , strtotime($order->order_date)) }}<b>: وقت الطلب </b><br> {{-- <b>Account:</b> 968-34567 --}}
            </div>
                       </td>
                   </tr>
               </table>
                        
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th>كود المزرعة</th>
                            <th>اسم المنتج</th>
                            <th>الوزن</th>
                                <th>صورة المنتج </th>
                                <th>الكمية</th>
                             
                            </tr>
                        </thead>
                        <tbody>
                                @php($sub_total = 0)
                                @foreach ($order->orderproducts as $orderItem)
                                 @php($sub_total += $orderItem->price * $orderItem->quantitiy)
                               <tr>
                               <td  >{{ $orderItem->product->vendor->code }}</td>

                               <td>{{ $orderItem->product->name_ar }}
                               
                               </td>
                               <td>{{ $orderItem->extraoption }}</td>
                               <td><img
                              src="{{ asset($orderItem->product->main_image_path) }}"
                              style="max-height: 150px;"/></td>
                                     <td>{{ $orderItem->quantity }}
                               
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
                        {{--  <p class="lead">Amount Due 2/22/2014</p>  --}}
    

                    </div>
                </div>
           
        </section>
    </div>

</body>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"> </script>
<script src="{{ asset('js/jQuery.print.js')}}"></script>
<script>
           jQuery.print('#masterContent');

</script>
</html>
