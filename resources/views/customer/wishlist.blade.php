
@extends('layout.app')
@section('content')

<div class="container innr-cont-area">
    <div class="row">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('home') }}">{{__('website.home_label')}}</a></li>
                <li class="active">{{ __('website.wish_list_label')}}</li>
            </ul>
        </div>

        @include('customer.includes.profile_menu')


            <wishlist :wish_list="{{ json_encode($products)}}"></wishlist>



</div><!--/.innr-cont-area-->

</div>
<div style="clear:both;height: 15px;"></div>
@include('includes.works');
@endsection
