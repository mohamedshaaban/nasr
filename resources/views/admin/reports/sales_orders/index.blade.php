@extends('admin::index') 
@section('content')

<section class="content-header">
    <h1>
        <span>Sales Reports</span>
    </h1>
</section>

<section class="content">
    @include('admin::partials.alerts')
    @include('admin::partials.exception')
    @include('admin::partials.toastr')
    @php
        $totalOrders = 0 ;
        $totalCommission = 0 ;
        $totalDeliveryCharges = 0 ;
        $totalSubTotal = 0 ;
        $totalForVendors = 0 ;
        $totalCommissionForVendors = 0 ;
        $bastaatCommission = 0 ;
        $knetCharges = 0 ;
        
            foreach ($allorders as $order  )
            {
            if(!isset($_GET['vendor_id'])||$_GET['vendor_id']=="")
            {
             $totalSubTotal +=$order->sub_total;
            }
               
                $totalDeliveryCharges+=$order->delivery_charges;
                foreach ($order->vendororderdeliverycharges as $orderVendorDetails )
                {
                
                         $totalCommission+=$orderVendorDetails->commission_kd;
                }
                foreach ($order->vendororderdeliverycharges as $orderVendorDetails ){
                $totalDeliveryCharges+=$orderVendorDetails->commission_kd ? $orderVendorDetails->commission_kd : 0;
               if(isset($_GET['vendor_id'])&&$_GET['vendor_id'] == $orderVendorDetails->vendor_id)
               {
               
                               $totalForVendors+= ($orderVendorDetails->total ) - $orderVendorDetails->commission_kd;
                               $totalOrders+=$orderVendorDetails->total;
                               $totalSubTotal+=$orderVendorDetails->total;
                               }
                               else 
                               {
                                $totalForVendors+= ($orderVendorDetails->total ) - $orderVendorDetails->commission_kd;
                                $totalOrders+=$orderVendorDetails->total;
                                $totalSubTotal+=$orderVendorDetails->total; 
                               }
                }
                if(!isset($_GET['vendor_id'])||$_GET['vendor_id']=="")
                {
                    $totalOrders+=$order->total;
                }
            }
            $bastaatCommission = number_format( ($totalOrders * 40 / 100),env('NUMBER_FORMAT'));
    @endphp
    <div class="box">
        <div class="box-header with-border">
            <div class="pull-right">
                <div class="btn-group" style="margin-right: 10px" data-toggle="buttons">
                    <label class="btn btn-sm btn-dropbox filter-btn " title="Filter">
                        <input type="checkbox"><i class="fa fa-filter"></i><span class="hidden-xs">&nbsp;&nbsp;Filter</span>
                    </label>
                </div>
            </div>
            <div class="box-header with-border {{ $request->has('search') ? '' : 'hide' }}" id="filter-box">
                <form action="{{ route('admin.reports.sales_orders') }}" class="form-horizontal" pjax-container="" method="get">
                    <input type="hidden" name="search" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-body">
                                <div class="fields-group">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"> Vendors</label>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-pencil"></i>
                                                </div>
                                                <select class="form-control select2" id="vendor_id" name="vendor_id" style="width: 100%;">
                                                   
                                                    <option  selected value="">Select Vendor</option>
                                                    @foreach ($vendors as $vendor)
                                                    <option {{ $request->has('vendor_id') && $request->vendor_id == $vendor->id ? 'selected' : '' }} value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Payment Methods</label>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-pencil"></i>
                                                </div>
                                                <select class="form-control select2" id="paymentmethod" name="paymentmethod" style="width: 100%;">

                                                    <option  selected value="">Select Payment</option>
                                                    @foreach ($paymentmethods as $method)
                                                        <option {{ $request->has('paymentmethod') && $request->paymentmethod == $method->id ? 'selected' : '' }} value="{{ $method->id }}">{{ $method->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"> From Date</label>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-pencil"></i>
                                                </div>
                                                <input class="form-control datepicker" id="from_date" placeholder="From Date" name="from_date" value="{{ $request->has('from_date') && !is_null($request->from_date) ? $request->from_date : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"> To Date</label>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-pencil"></i>
                                                </div>
                                                <input class="form-control datepicker" id="to_date" placeholder="To Date" name="to_date" value="{{ $request->has('to_date') && !is_null($request->to_date) ? $request->to_date : '' }}">

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
                                        <button class="btn btn-info submit btn-sm"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
             <div class="pull-right">
                            <div class="col-md-12">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <div class="btn-group pull-left">
                                        <button class="btn btn-info submit btn-sm" onclick="exportTableToExcel('tbexport','')"><i class="fa fa-search"></i>&nbsp;&nbsp;Export</button>
                                    </div>
                                </div>
                            </div>
                        </div>
            
            <div class="box-body table-responsive no-padding">
                
                <table class="table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th>Customer Full Name</th>
                            <th>Order No.</th>
                            <th>Order Date</th>
                            <th>Order Status</th>
                            <th>Payment Method</th>

                            <th>Product Name</th>
                            <th>Amount per Item</th>
                            <th>Sub Total of Order</th>
                            <th>Delivery Charges of Order</th>

                            <th>Knet Charges </th>
                            <th>Interal commission</th>
                            <th>Total Amount For Vendor</th>
                            <th>Total Amount of Order(Buyer)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order  )
                      
                        @if((isset($order->ordertrack->last()->orderstatus->id)&&$order->ordertrack->last()->orderstatus->id == $request->status &&  $request->has('status')) || (! $request->has('status')) )
                        <tr>
                            <td>@if($order->is_guest==0){{ optional($order->User)->name }}@else {{ optional($order->guestusers)->name }} @endif</td>
                            <td>{{ $order->unique_id }}</td>
                            <td>{{ $order->created_at }}</td>
                            @if((isset($order->ordertrack->last()->orderstatus->id)))<td>{{ $order->ordertrack->last()->orderstatus->title_en }}</td>
                            @else 
                            <td></td>
                            @endif
                            <Td>{{ $order->paymentmethods->title }}</Td>
                            <td>
                                @foreach ($order->orderproducts as $item )
                                    {{ optional($item->product)->name_en  }} <br/>
                                @endforeach
                            </td>
                            <td>
                                 @foreach ($order->orderproducts as $item )
                                @if(!isset($_GET['vendor_id'])||$_GET['vendor_id']=="")
                                        {{ $item->sub_total  }} X {{ $item->quantity  }}  <br />
                                        @elseif($_GET['vendor_id'] && $_GET['vendor_id'] == $item->vendor_id)
                                        {{ $item->sub_total  }} X {{ $item->quantity  }}  <br />
                                        @endif
                                @endforeach
                            </td>
                            <td>
                                @if(!isset($_GET['vendor_id'])||$_GET['vendor_id']=="")
                                {{ number_format($order->sub_total,env('NUMBER_FORMAT')) }}
                                @elseif($_GET['vendor_id'])
                                <?php $vendTota = 0 ;  ?>
                                 @foreach ($order->orderproducts as $item )
                                @if($_GET['vendor_id'] == $item->vendor_id)
                                <?php $vendTota+=$item->sub_total; ?> 
                                @endif
                                {{ number_format($vendTota,env('NUMBER_FORMAT')) }}
                                 @endforeach
                                @endif
                                </td>
                            <td>{{ number_format($order->delivery_charges ,env('NUMBER_FORMAT'))}}</td>

                            <td> 
                                @foreach ($order->vendororderdeliverycharges as $orderVendorDetails )
                                @php
                                
                                @endphp
                                {{ number_format($orderVendorDetails->commission_kd ? $orderVendorDetails->commission_kd : 0,env('NUMBER_FORMAT')) }} <br />
                                @endforeach
                               
                            </td>
                            <td>
                            @foreach ($order->vendororderdeliverycharges as $orderVendorDetails )
                                   <?php $totalCommissionForVendors+=number_format(( $orderVendorDetails->total-($orderVendorDetails->total * $orderVendorDetails->commission_percentage / 100)),env('NUMBER_FORMAT'));
                                   
                                   ?>
                                   {{ number_format((  ($orderVendorDetails->total * $orderVendorDetails->commission_percentage / 100)),env('NUMBER_FORMAT')) }} <br />
                                    @endforeach
                             
                            </td>
                            <td>
                                   @foreach ($order->vendororderdeliverycharges as $orderVendorDetails )
                                   
                                   {{ number_format((  ($orderVendorDetails->total * $orderVendorDetails->commission_percentage / 100) - ($orderVendorDetails->commission_kd ? $orderVendorDetails->commission_kd : 0)),env('NUMBER_FORMAT')) }} <br />
                                    @endforeach
                            </td>
                            @if(!isset($_GET['vendor_id'])||$_GET['vendor_id']=="")
                            <td>{{ number_format($order->total,env('NUMBER_FORMAT')) }}</td>
                            
                            @elseif(isset($_GET['vendor_id']))
                            <?php $totalOrderForVendors=0; 
                            foreach ($order->vendororderdeliverycharges as $orderVendorDetails ){
               if(isset($_GET['vendor_id'])&& $_GET['vendor_id'] == $orderVendorDetails->vendor_id)
               {
                               $totalOrderForVendors+= ($orderVendorDetails->total ) ;
                               }
                               
                }?>
                <td>
                {{ number_format($totalOrderForVendors,env('NUMBER_FORMAT')) }}
                </td>
                            @endif
                        </tr>
                        @endif
                       @endforeach
                    </tbody>
                </table>  
                <ul class="pagination pagination-sm no-margin pull-right">
                        {!! $orders->appends($_GET)->links() !!}
                </ul>
                <table class="table table-bordered table-hover ">
                    <thead>
                    <tr>
                        <th>Total Payed amount</th>
                        <th>Bastaat Commission </th>
                        <th>Remaining for vendor</th>
                        <th>Total Knet Charges </th>

                        <th>Total Amount For Vendors</th>
                     </tr>
                    </thead>
                    <thead>
                    <tr>
                        <th>{{ number_format($totalSubTotal,env('NUMBER_FORMAT'))  }}</th>
                        <th>{{ $bastaatCommission  }}</th>
                        <th>{{ number_format(intval($totalSubTotal) - intval($bastaatCommission) ,env('NUMBER_FORMAT'))  }}</th>
                        <th>{{ number_format( $totalDeliveryCharges,env('NUMBER_FORMAT'))  }}</th>
                        
                         <th>{{ number_format(intval($totalSubTotal) - intval($bastaatCommission)-intval($totalDeliveryCharges),env('NUMBER_FORMAT'))  }}</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <table class="table table-bordered table-hover " id="tbexport" style="display:none;">
                    <thead>
                        <tr>
                            <th>Customer Full Name</th>
                            <th>Order No.</th>
                            <th>Order Date</th>
                            <th>Order Status</th>
                            <th>Payment Method</th>

                            <th>Product Name</th>
                            <th>Amount per Item</th>
                            <th>Sub Total of Order</th>
                            <th>Delivery Charges of Order</th>

                            <th>Knet Charges </th>
                            <th>Interal commission</th>
                            <th>Total Amount For Vendor</th>
                            <th>Total Amount of Order(Buyer)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allorders as $order  )
                      
                        @if((isset($order->ordertrack->last()->orderstatus->id)&&$order->ordertrack->last()->orderstatus->id == $request->status &&  $request->has('status')) || (! $request->has('status')) )
                        <tr>
                            <td>@if($order->is_guest==0){{ optional($order->User)->name }}@else {{ optional($order->guestusers)->name }} @endif</td>
                            <td>{{ $order->unique_id }}</td>
                            <td>{{ $order->created_at }}</td>
                            @if((isset($order->ordertrack->last()->orderstatus->id)))<td>{{ $order->ordertrack->last()->orderstatus->title_en }}</td>
                            @else 
                            <td></td>
                            @endif
                            <Td>{{ $order->paymentmethods->title }}</Td>
                            <td>
                                @foreach ($order->orderproducts as $item )
                                    {{ optional($item->product)->name_en  }} <br/>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($order->orderproducts as $item )
                                @if(!isset($_GET['vendor_id'])||$_GET['vendor_id']=="")
                                        {{ $item->sub_total  }} X {{ $item->quantity  }}  <br />
                                        @elseif($_GET['vendor_id'] && $_GET['vendor_id'] == $item->vendor_id)
                                        {{ $item->sub_total  }} X {{ $item->quantity  }}  <br />
                                        @endif
                                @endforeach
                            </td>
                            <td>
                                @if(!isset($_GET['vendor_id'])||$_GET['vendor_id']=="")
                                {{ number_format($order->sub_total,env('NUMBER_FORMAT')) }}
                                @elseif($_GET['vendor_id'])
                                <?php $vendTota = 0 ;  ?>
                                 @foreach ($order->orderproducts as $item )
                                @if($_GET['vendor_id'] == $item->vendor_id)
                                <?php $vendTota+=$item->sub_total; ?> 
                                @endif
                                {{ number_format($vendTota,env('NUMBER_FORMAT')) }}
                                 @endforeach
                                @endif
                                </td>
                            <td>{{ number_format($order->delivery_charges ,env('NUMBER_FORMAT'))}}</td>

                            <td> 
                                @foreach ($order->vendororderdeliverycharges as $orderVendorDetails )
                                @php
                                
                                @endphp
                                {{ number_format($orderVendorDetails->commission_kd ? $orderVendorDetails->commission_kd : 0,env('NUMBER_FORMAT')) }} <br />
                                @endforeach
                               
                            </td>
                            <td>
                            @foreach ($order->vendororderdeliverycharges as $orderVendorDetails )
                                   <?php $totalCommissionForVendors+=number_format(( $orderVendorDetails->total-($orderVendorDetails->total * $orderVendorDetails->commission_percentage / 100)),env('NUMBER_FORMAT'));
                                   
                                   ?>
                                   {{ number_format((  ($orderVendorDetails->total * $orderVendorDetails->commission_percentage / 100)),env('NUMBER_FORMAT')) }} <br />
                                    @endforeach
                             
                            </td>
                            <td>
                                   @foreach ($order->vendororderdeliverycharges as $orderVendorDetails )
                                   
                                   {{ number_format((  ($orderVendorDetails->total * $orderVendorDetails->commission_percentage / 100) - ($orderVendorDetails->commission_kd ? $orderVendorDetails->commission_kd : 0)),env('NUMBER_FORMAT')) }} <br />
                                    @endforeach
                            </td>
                            @if(!isset($_GET['vendor_id'])||$_GET['vendor_id']=="")
                            <td>{{ number_format($order->total,env('NUMBER_FORMAT')) }}</td>
                            
                            @elseif(isset($_GET['vendor_id']))
                            <?php $totalOrderForVendors=0; 
                            foreach ($order->vendororderdeliverycharges as $orderVendorDetails ){
               if(isset($_GET['vendor_id'])&& $_GET['vendor_id'] == $orderVendorDetails->vendor_id)
               {
                               $totalOrderForVendors+= ($orderVendorDetails->total ) ;
                               }
                               
                }?>
                <td>
                {{ number_format($totalOrderForVendors,env('NUMBER_FORMAT')) }}
                </td>
                            @endif
                        </tr>
                        @endif
                       @endforeach
                    </tbody>
                </table>
            </div>
            <div class="box-footer clearfix">
            </div>
        </div>


    </div>
</section>

<script src="{{ asset('vendor/laravel-admin/AdminLTE/plugins/select2/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.filter-btn').click(function(){
            if($('#filter-box').hasClass('hide')){
                $('#filter-box').removeClass('hide')
            }else{
                $('#filter-box').addClass('hide');
            }
        });
    });

</script>

<script type="text/javascript">
    $(function () {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });
    });
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel;charset=utf-8;';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}
</script>
@endsection
