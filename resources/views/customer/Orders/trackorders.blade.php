@extends('layout.app')
@section('content')

    <div class="sec-crumb forgot-pass">

    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ route('home') }}">{{__('website.home_label')}}</a></li>
            <li class="active">{{__('website.track_orders_label')}}</li>
        </ul>
    </div>

            @if(count($orders) < 1)
                @include('includes.no_content' ,['title' => __('website.no_orders_found_label') , 'description' => __('website.no_orders_desc_found_label')])
             @endif
    <div class="container innr-cont-area">
        <div class="row">
            
            @foreach($orders as $order)
            <div class="col-md-9 trackmy-order mt-20">
                <div class="order-id">{{ __('website.Order Id')}}: #{{ $order->unique_id }}</div>
                <h4>{{ __('website.Customername_label')}}: {{ Auth::user()->name }}</h4>
                <span class="date">{{ __('website.date_label')}}: {{ $order->order_date }}</span>
                @php
                $vendorOrderItems = \App\Models\OrderProduct::where('order_id' , $order->id)->groupBy('vendor_id')->pluck('vendor_id');

                @endphp
                @foreach($vendorOrderItems as $key )
                    @php
                        $vendor  = \App\Models\Vendors::find($key);

                    @endphp
                @if($vendor)
                <div class="table-head">
                    <span>{{ $vendor->name }}</span>
                    <small>{{ __('website.code_label')}}: {{ $vendor->code }}</small>
                </div>
                    @endif
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('website.order_Status_label')}}</th>
                        <th scope="col">{{ __('website.order_updated_label')}}</th>
                    </tr>
                    </thead>
                    <tbody>
@php
$ordertrack = \App\Models\OrderTrack::where('order_id', $order->id)->where('vendor_id' , $key)->with('orderstatus')->get();
$orderproducts = \App\Models\OrderProduct::where('order_id', $order->id)->where('vendor_id' , $key)->with('product')->get();

@endphp

                    @foreach($ordertrack as $track)
                    <tr>
                        <td>{{ $track->orderstatus->title }}</td>
                        <td>{{ $track->created_at }}</td>
                    </tr>
                    @endforeach

                    <tr>
                        <td colspan="2">
                            <div class="show-items clearfix">
                                <a class="heading" data-toggle="collapse" data-parent="#stacked-menu" href="#items{{ $order->id }}" aria-expanded="true">{{ __('website.show_items_label')}}</a>
                                <div class="items{{ $order->id }} collapse in" id="items{{ $order->id }}" aria-expanded="true" style="">
                                    <strong>{{ __('website.item_label')}}</strong>
                                    @foreach($orderproducts as $item)
                                    <p>{{ optional($item->product)->name }}</p>
                                    <small>{{ $item->extraoption }}<br> {{ __('website.qty_label')}}: {{ $item->quantity }}</small>
                                    <br>
                                    <br>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                    @endforeach

            </div><!--/.col-sm-9-->
                @endforeach
        </div>
    </div><!--/.innr-cont-area-->

    @include('includes.works');

@endsection
