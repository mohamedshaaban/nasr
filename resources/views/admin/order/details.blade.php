<section class="content">
<style>
    @media print {
        .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
            float: left;
        }
        .col-sm-12 {
            width: 100%;
        }
        .col-sm-11 {
            width: 91.66666667%;
        }
        .col-sm-10 {
            width: 83.33333333%;
        }
        .col-sm-9 {
            width: 75%;
        }
        .col-sm-8 {
            width: 66.66666667%;
        }
        .col-sm-7 {
            width: 58.33333333%;
        }
        .col-sm-6 {
            width: 50%;
        }
        .col-sm-5 {
            width: 41.66666667%;
        }
        .col-sm-4 {
            width: 33.33333333%;
        }
        .col-sm-3 {
            width: 25%;
        }
        .col-sm-2 {
            width: 16.66666667%;
        }
        .col-sm-1 {
            width: 8.33333333%;
        }
        .col-sm-pull-12 {
            right: 100%;
        }
        .col-sm-pull-11 {
            right: 91.66666667%;
        }
        .col-sm-pull-10 {
            right: 83.33333333%;
        }
        .col-sm-pull-9 {
            right: 75%;
        }
        .col-sm-pull-8 {
            right: 66.66666667%;
        }
        .col-sm-pull-7 {
            right: 58.33333333%;
        }
        .col-sm-pull-6 {
            right: 50%;
        }
        .col-sm-pull-5 {
            right: 41.66666667%;
        }
        .col-sm-pull-4 {
            right: 33.33333333%;
        }
        .col-sm-pull-3 {
            right: 25%;
        }
        .col-sm-pull-2 {
            right: 16.66666667%;
        }
        .col-sm-pull-1 {
            right: 8.33333333%;
        }
        .col-sm-pull-0 {
            right: auto;
        }
        .col-sm-push-12 {
            left: 100%;
        }
        .col-sm-push-11 {
            left: 91.66666667%;
        }
        .col-sm-push-10 {
            left: 83.33333333%;
        }
        .col-sm-push-9 {
            left: 75%;
        }
        .col-sm-push-8 {
            left: 66.66666667%;
        }
        .col-sm-push-7 {
            left: 58.33333333%;
        }
        .col-sm-push-6 {
            left: 50%;
        }
        .col-sm-push-5 {
            left: 41.66666667%;
        }
        .col-sm-push-4 {
            left: 33.33333333%;
        }
        .col-sm-push-3 {
            left: 25%;
        }
        .col-sm-push-2 {
            left: 16.66666667%;
        }
        .col-sm-push-1 {
            left: 8.33333333%;
        }
        .col-sm-push-0 {
            left: auto;
        }
        .col-sm-offset-12 {
            margin-left: 100%;
        }
        .col-sm-offset-11 {
            margin-left: 91.66666667%;
        }
        .col-sm-offset-10 {
            margin-left: 83.33333333%;
        }
        .col-sm-offset-9 {
            margin-left: 75%;
        }
        .col-sm-offset-8 {
            margin-left: 66.66666667%;
        }
        .col-sm-offset-7 {
            margin-left: 58.33333333%;
        }
        .col-sm-offset-6 {
            margin-left: 50%;
        }
        .col-sm-offset-5 {
            margin-left: 41.66666667%;
        }
        .col-sm-offset-4 {
            margin-left: 33.33333333%;
        }
        .col-sm-offset-3 {
            margin-left: 25%;
        }
        .col-sm-offset-2 {
            margin-left: 16.66666667%;
        }
        .col-sm-offset-1 {
            margin-left: 8.33333333%;
        }
        .col-sm-offset-0 {
            margin-left: 0%;
        }
    }
    </style>


  <div class="row">
    <div class="col-sm-12">
      <div class="row">
        <div class="col-sm-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">&nbsp;</h3>
              <div class="box-tools">
                <div class="btn-group pull-right" style="margin-right: 5px">
                  <a href="{{ route('admin_order.index') }}" class="btn btn-sm btn-default" title="List"><i
                        class="fa fa-list"></i><span class="hidden-xs">&nbsp;List</span></a>
                </div>
                <div class="btn-group pull-right" style="margin-right: 5px">
                     @if(Admin::user()->isAdministrator())
                <a href="{{ route('customer.order.print_invoice',$order->id) }}"  target="_blank" class="btn btn-default"><button class="btn btn-sm btn-primary"  title="Print"><i class="fa fa-print"></i><span
                        class="hidden-xs">&nbsp;Print</span></button>
                        </a>
                        @else

                         <a href="{{ route('delivery.order.print_invoice',$order->id) }}"  target="_blank" class="btn btn-default"><button class="btn btn-sm btn-primary"  title="Print"><i class="fa fa-print"></i><span
                        class="hidden-xs">&nbsp;Print</span></button>
                        </a>
                        @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="details">
        <div class="row">
            @if(Admin::user()->isAdministrator())
          <div class="col-sm-4">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">تفاصيل الطلب</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                <!-- form start -->
                <table class="table table-hover">
                  <tr>
                     <td style="width: 40%;">رقم الطلب : </td>
                    <td>{{ $order->unique_id }}</td>

                  </tr>






                </table>
              </div>
            </div>
          </div>
          @endif
          <div class="col-sm-4">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">تفاصيل العميل</h3>
              </div>
              <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                  <tr>
                    <td style="width: 40%;">اسم العميل : </td>
                      @if($order->is_guest==1)
                          <td>{{ $order->guestusers->name }}</td>
                          @else
                    <td>{{ $order->user->name }}</td>
                          @endif
                  </tr>
                  <tr>
                    <td style="width: 40%;">العنوان : </td>
                    <td>
                      @php

                      $add = 'الدولة  : '.optional(optional($order->userAddress)->countries)->name_ar.'<br />';

                      $add .= 'المدينة  : '.optional($order->userAddress)->city.'<br />';

                      $add .= 'المحافظة :'.optional($order->userAddress)->province.'<br />';
                      $add .= 'القطعة : '.optional($order->userAddress)->block.'<br />';
                      $add .= 'المنطقة : '.optional($order->useraddress->area)->name_ar.'<br />';
                      $add .= 'الشارع :'.optional($order->userAddress)->street.'<br />';
                      $add .= 'الجادة : '.optional($order->userAddress)->avenue.'<br />';
                      $add .= 'رقم المنزل  : '.optional($order->userAddress)->fax.'<br />';
                      $add .= 'الطابق : '.optional($order->userAddress)->floor.'<br />';
                      $add .= 'الشقة : '.optional($order->userAddress)->flat.' ';


                      echo trim($add, ',');
                      @endphp
                    </td>
                  </tr>
                  <tr>
                    <td style="width: 40%;">رقم الهاتف : </td>
                    <td>{{ optional($order->userAddress)->phone_no }} - {{ optional($order->userAddress)->mobile }}</td>
                  </tr>
                  <tr>
                    <td>تاريخ الطلب  : </td>
                    <td>{{ $order->order_date }}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">تفاصيل الطلب</h3>
              </div>
              <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tr>
                        <td>لطلب خاص ب  : </td>
                        <td>{{ optional($order->orderDestination)->title }}</td>
                    </tr><tr>
                        <td>مقدم الطلب : </td>
                        <td>{{ optional($order->orderRequesters)->title }}</td>
                    </tr><tr>
                        <td>التصنيف : </td>
                        <td>{{ optional($order->category)->name_en }}</td>
                    </tr><tr>
                        <td>تفاصيل الطب : </td>
                        <td>{{ ($order->order_extra) }}</td>
                    </tr>



                  <tr>
                    <td>حالة الطلب</td>
                    <td>{{ optional($order->orderstatus)->title_en }}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">حالة الطلب</h3>
              </div>
              <div class="box-body">
                  <table class="table table-hover">
                @foreach($statusHis as $statusRow)

                  <div class="row">

                      <div class="col-sm-6 col-sm-offset-3">
                          <tr>
                              <td>

                                  <label>{{ optional($statusRow->vendor)->name }}</label>
                              </td>

                              <td>
                                  {{ optional($statusRow->orderstatus)->title_en }}
                              </td>
                             <td> <span class="pull-right">{{ $statusRow->created_at }}</span>
                             </td>
                          </tr>
                          <tr>
                              <td>

                                  <label>{{ optional($statusRow->vendor)->code }}</label>
                              </td>
                          </tr>
                  </div>
                  </div>
                  <div class="row">
                    {{--<div class="col-md-6 col-md-offset-3"><pre style="min-height: 39px">{{ $statusRow->comment }}</pre></div>--}}
                  </div>
                @endforeach
                  </table>
              </div>
            </div>
          </div>
        </div>
        @if(Admin::user()->isAdministrator())
             <div class="row">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">تفاصيل الدفع</h3>
              </div>
              <div class="box-body">
                  <table class="table table-hover">
                @foreach($order->ordertransactions as $ordertransactions)

                  <div class="row">

                      <div class="col-sm-6 col-sm-offset-3">
                          <tr>
                              <td>

                                  <label>رقم العملية : {{ ($ordertransactions->payment_id) }}</label>
                              </td>

                              <td >
                              <span class="pull-right"> النتيجة : {{ ($ordertransactions->result) }}</span>
                              </td>
                             <td> <span class="pull-right">المبلغ : {{ $ordertransactions->amount }}</span>
                             </td>
                               <td> <span class="pull-right">الرقم المرجعي : {{ $ordertransactions->tran_id }}</span>
                             </td>
                          </tr>

                  </div>
                  </div>

                @endforeach
                  </table>
              </div>
            </div>
          </div>
        </div>
        @endif
        <div class="row">
          <div class="col-sm-12">
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">المنتجات</h3>
              </div>
              <div class="form-horizontal">
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <thead>
                    <tr>
                      <th class="text-center" style="width: 100px">رقم المنتج</th>
                      <th class="text-center" style="width: 100px">رSku</th>
                      <th class="text-center" style="width: 200px">الصورة </th>
                        <th style="width: 200px">اسم المنتج</th>
                      <th style="width: 200px"></th>
                      <th class="text-center" style="width: 50px">الكمية</th>


                        <th class="text-center" style="width: 100px"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                      $subTotal = 0;
                    @endphp

                    @php
                        $products = $order->orderProducts->groupBy('vendor_id');

                    @endphp
                    @foreach($products as $key=>$vendors)
                        @php
                            $vendor = \App\Models\Vendors::find($key);

                        @endphp
                        @foreach($vendors as $key=>$orderProduct)                      <tr>
                        <td class="text-center">{{ $orderProduct->product_id }}</td>
                        <td class="text-center">{{ $orderProduct->product->sku }}</td>
                        <td class="text-center"><img
                              src="{{ $orderProduct->product->main_image_path }}"
                              style="max-height: 150px;"/></td>
                        <td>{{ $orderProduct->product->name_ar }}</td>
                        <td>
{{ $orderProduct->extraoption }}
                        </td>

                        <td class="text-center">{{ $orderProduct->quantity }}</td>

                      </tr>
                      @php
                      @endphp
                    @endforeach
                    @endforeach

                    </tbody>
                    <tfoot>

                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
<script>

  function load() {
    $('#printBtn').click(function () {



  var divToPrint=document.getElementById('details');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},10);


      }
    )
    ;
  }

  if (document.readyState === 'complete') {
    load();
  }
  else {
    $(document).ready(load);
  }
</script>
