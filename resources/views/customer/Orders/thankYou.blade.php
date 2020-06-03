@extends('layout.app')
@section('content')



    <div class="container">
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">{{__('website.home_label')}}</a></li>
        <li class="active">{{ __('website.thank_you_label')}}</li>
    </ul>
</div>

<div class="container innr-cont-area thanku-page">
    <h3 class="innerpage-head">{{ __('website.thank_you_label')}}</h3>
    <hr>
    <div class="">
        <div>{{ __('website.your_order_number_label')}} <span class="green">{{ $order->unique_id }}</span></div>
        <span>{{ __('website.email_order_label')}}</span>
        <br><br>
        <a href="{{ route('home') }}"> <div class="inline-block"><button class="btn-lg btn-primary rounded-0">{{ __('website.Continue_Shopping')}}</button></a>
        </div>
    </div>
</div><!--/.innr-cont-area-->

    @include('includes.works');
@endsection
