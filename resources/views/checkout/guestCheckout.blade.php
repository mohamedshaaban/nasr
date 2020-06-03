@extends('layout.app')
@section('content')

@php
$totalcrt = 0 ;
$totalcount = 0 ;
$total  = 0 ;
$itemsQuantity=array();
foreach($cartItems as $item)
{
    $totalcount++ ;
    $itemsQuantity[$item->product_id]=$item->quantity;
  $totalcrt += $item->total;
}
        $today = \Carbon\Carbon::today()->format('Y-m-d');
        $discount = \App\Models\Coupon::where('code',session('couponCode'))->where('status', 1)->where('from', '<=', $today)->where('to', '>=', $today)->first();
$totalDiscount = 0 ;


        if ($discount ) {
            if ($discount->is_fixed) {

                $grand_total =  $totalcrt - ($discount->fixed  );
                $discount = $discount->fixed ;
            } else {

                $amount = $totalcrt * $discount->percentage / 100;
                $grand_total = $totalcrt - $amount ;
                $discount = $totalcrt * $discount->percentage / 100;
            }
        }
        else
        {
            $grand_total =$totalcrt ;
        }
        $grand_total = $grand_total>0 ? $grand_total : 0 ;
$totalAfterGroup= 0 ;
  $discount = number_format((float)($discount), 2, '.', '');
@endphp
<div class="container">
    <ul class=" breadcrumb">
        <li><a href="{{ route('home') }}">{{__('website.home_label')}}</a></li>
        <li class="active"  >{{__('website.delivery_address_label')}}</li>
    </ul>
</div>

<div class="container innr-cont-area">
    <div class="row">
        <div class="col-sm-12 mt-30">
            <h3 class="innerpage-head">{{__('website.delivery_address_label')}}</h3>
        </div>
        <div class="col-sm-7 checkout">
            <ul class="nav nav-tabs" id="payment-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#Step1" aria-controls="home" role="tab" data-toggle="tab">
                        {{__('website.delivery_address_label')}}
                    </a></li>
                <li role="presentation"><a href="#Step2" style="  pointer-events: none;cursor: default;" aria-controls="profile" role="tab" data-toggle="tab">
                        {{__('website.review_payment_label')}}
                    </a></li>
            </ul>

            <!-- Tab panes -->

            @if(Auth::user())

                <checkout-registered :useraddress="{{ json_encode($userAddress) }}" :coupon="{{ $coupon }}" :payment_methods="{{ json_encode($payment_methods) }}" :countries="{{ json_encode($countries) }}" :governates="{{ json_encode($governates) }}" :areas="{{ json_encode($areas) }}"></checkout-registered>
                @else

                <checkout-checkout :countries="{{ json_encode($countries) }}" :coupon="{{ $coupon }}"  :payment_methods="{{ json_encode($payment_methods) }}"  :governates="{{ json_encode($governates) }}" :areas="{{ json_encode($areas) }}"></checkout-checkout>
                @endif
        </div><!--/.col-sm-8-->

        <div class="col-sm-5">
            <div class="side-summary">
                <h3>{{__('website.order_summary_label')}}</h3>
                <div class="data clearfix">
                    <div class="row"  style="display: none">
                        <div class="col-xs-6 list">
                            {{__('website.itemtottal_label')}}
                        </div>
                        <div class="col-xs-6 list text-right">
                            <span >{{ number_format((float)($totalcrt), 2, '.', '') }}</span> {{__('website.kd_label')}}
                        </div>
                    </div>
                    <div class="row"  style="display: none">
                        <div class="col-xs-6 list">
                            {{__('website.total_delivery_label')}}
                        </div>
                        <div class="col-xs-6 list text-right">
                            <span id="delivery_charges" >{{  number_format((float)( $delivery_charges ), 2, '.', '')  }}</span> {{__('website.kd_label')}}
                        </div>
                    </div>
                    <div class="row" style="display: none">
                        <div class="col-xs-6 list">
                            {{__('website.Subtottal_label')}}
                        </div>
                        <div class="col-xs-6 list text-right">
                            <span id="total_delivery_charges">{{ number_format((float)($delivery_charges+$totalcrt), 2, '.', '') }}</span> {{__('website.kd_label')}}
                        </div>
                    </div>
                    <div class="row" id="discountcodeDiv" @if($discount==0.00 ) style="display: none" @endif>
                        <div class="col-xs-6 list">
                            {{__('website.discount_code')}}  ( {{ session('couponCode') }} )
                        </div>

                        <div class="col-xs-6 list text-right" @if($discount==0.00 ) style="display: none" @endif>
                            <span id="discountcodeVal"> - {{ number_format((float)($discount), 2, '.', '') }}</span> {{__('website.kd_label')}}
                        </div>
                    </div>
                    <div class="row"  style="display: none">
                        <div class="col-xs-12">
                            <div class="total">
                                <div class="row">
                                    <div class="col-xs-6">
                                        {{__('website.order_total_label')}}
                                    </div>
                                    <div class="col-xs-6 text-right">
                                       <span id="ordertotal">{{ number_format((float)($delivery_charges+$grand_total), 2, '.', '')  }}</span> {{__('website.kd_label')}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="heading" data-toggle="collapse" data-parent="#stacked-menu" href="#summary" aria-expanded="true">{{ $totalcount }} {{__('website.items_in_cart')}}</a>
                <ul class="collapse in listing" id="summary" aria-expanded="true" style="">
                    @foreach($cartItems as $item)
                    <li class="row">
                        <div class="col-sm-4 img">
                            <img src="{{ asset( $item->image) }}">
                        </div>
                        <div class="col-sm-4 pl-0 data">
                            <strong>{{ $item->product->name }}</strong><br>
                            <span class="text-drkgreen">{{__('website.code_label')}}:{{ $item->product->vendor->code }}</span>
                            <small>  {{ $item->product->code }}</small><br>
                            {{__('website.qty_label')}}: {{ $item->quantity }} <br>
                        </div>
                        <div class="col-sm-4 price">
                         </div>
                    </li><!--/li-->
                    @endforeach
                </ul><!--/nav-->
            </div>
        </div>

    </div>

</div><!--/.innr-cont-area-->

@include('includes.works');


@endsection
