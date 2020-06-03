@extends('layout.app')
@section('content')

    <div class="container innr-cont-area shopping-cart">

    <checkout-my-cart :min_order="{{ $min_order }}" :cart_list="{{ ($groupedcart)}}" :discountamount="{{ $discountamount }}"  ></checkout-my-cart>
    </div>
@endsection
