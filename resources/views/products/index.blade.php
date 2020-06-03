
@extends('layout.app')
@section('content')

    <div class="container innr-cont-area">
        <div class="row">
            @if($offers)
                <productsoffers
                    :categoryname="Offers"

                    :products_list="{{ json_encode($products)}}"></productsoffers>
                @else

                <products
                    :categoryname='"{{ $categoryname }}"'

                    :products_list="{{ json_encode($products)}}"></products>
            @endif
        </div>

</div>
    @include('includes.works');

@endsection
