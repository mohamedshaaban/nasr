
@extends('layout.app')
@section('content')

    <div class="container innr-cont-area">
        <div class="row">
            @if($offers)
                <productsoffers
                    :categoryname="Offers"
                    :selected_categories="{{ ($selectedCategories)}}"
                    :categories="{{$categories}}"
                    :vendors="{{ $vendors }}"
                    :products_list="{{ json_encode($products)}}"></productsoffers>
                @else
                <products-search
                    :categoryname='"{{ $categoryname }}"'
                    :selected_categories="{{ ($selectedCategories)}}"
                    :categories="{{$categories}}"
                    :vendors="{{ $vendors }}"
                    :products_list="{{ json_encode($products)}}"></products-search>
            @endif
        </div>

</div>
    @include('includes.works');

@endsection
