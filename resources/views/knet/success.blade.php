@extends('layout.app')
@section('content')
    <div class="sec-crumb forgot-pass">

        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('home') }}">{{__('website.home_label')}}</a></li>
                <li class="active">{{__('website.track_orders_label')}}</li>
            </ul>
        </div>
        <div class="container innr-cont-area">
            <div class="row">

                <div class="col-md-9 trackmy-order mt-20">
                    <div class="order-id">{{ __("website.order_processed_label") }}</div>

                    <h4>{{ __("website.order No") }} : {{ $order->unique_id }}</h4>
                    <h4>{{ __("website.knet_info_label") }}</h4>
                    <span class="date">{{ __('website.date_label')}}:  {{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>


                        <table class="table">
                            <thead>

                            </thead>
                            <tbody>


                            @foreach($orderTransaction->toArray() as $key=>$value)

                                <tr>
                                    <td>{{ ucwords(str_replace("_", " ",$key)) }} </td>
                                    <td>{{ $value }} @if(ucwords(str_replace("_", " ",$key))=='Amount') {{__('website.KWd')}} @endif</td>
                                </tr>
                            @endforeach


                            </tbody>
                        </table>


                </div><!--/.col-sm-9-->

            </div>
        </div>


@endsection
