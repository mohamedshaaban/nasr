@extends('layout.app')
@section('content')



    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ route('home') }}">{{__('website.home_label')}}</a></li>

            <li><a href="/products?categories={{ $product->categories->last()->id }}">{{ $product->categories->last()->name }}</a></li>
            <li class="active">{{ $product->name }}</li>
        </ul>
    </div>

    <div class="container innr-cont-area">
        <div class="product-details">
            <div class="row">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="single-item slider-for">
                                <div class="single-item-img">
                                    <img src="{{ $product->main_image_path }}" alt="">
                                </div>
                                @foreach ($product->images_path as $image)
                                    <div class="single-item-img">
                                        <img src="{{$image}}" alt="">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="multiple-item slider-nav">
                                <img src="{{ $product->main_image_path }}" alt="">
                                @foreach ($product->images_path as $image)

                                    <img src="{{$image}}" alt="">

                                @endforeach
                            </div>
                        </div>
                    </div>
                </div><!--/.col-sm-6-->
                <div class="col-md-5 data">
                    <h2>{{ $product->name }}</h2>
                    <div> <b>{{ __('website.by_label')}}</b> {{ $product->vendor->code }}</div>
                    <div><b></b></div>
                    <div
                        class="green">@if($product->categories) @foreach ($product->categories as $category ) {{  $category->name }} @endforeach @endif</div>
                    <div class="row">
                        <div class="col-sm-4 col-md-5">
                            <div class="rate-cvr clearfix">
                                {{--<div class="rate-big">--}}
                                    {{--<input type="radio" disabled="disabled" id="star25" name="rate2" checked="checked" value="25">--}}
                                    {{--<label for="star25" title="text">5 stars</label>--}}
                                    {{--<input type="radio" disabled="disabled" id="star24" name="rate2" checked="checked" value="24">--}}
                                    {{--<label for="star24" title="text">4 stars</label>--}}
                                    {{--<input type="radio" disabled="disabled" id="star23" name="rate2" value="23">--}}
                                    {{--<label for="star23" title="text">3 stars</label>--}}
                                    {{--<input type="radio" disabled="disabled" id="star22" name="rate2" value="22">--}}
                                    {{--<label for="star22" title="text">2 stars</label>--}}
                                    {{--<input type="radio" disabled="disabled" id="star21" name="rate2" value="21">--}}
                                    {{--<label for="star21" title="text">1 star</label>--}}
                                {{--</div>--}}
                            </div>
                        </div>

                    </div><!--/.row-->
                    <div class="price clearfix">
                        <div class="pull-left">
                            @if($product->offer)
                            <strong class="old" id="prodoldprice">{{ number_format((float)($product->price), 3, '.', '') }}{{__('website.kd_label')}}</strong>
                            @endif
                            <strong id="prodPrice" style="display: none">{{ $product->offer ? $product->offer->is_fixed ? number_format((float)(($product->price-($product->offer->fixed))>0?($product->price-($product->offer->fixed)):0), 2, '.', '') : number_format((float)(($product->price-(($product->price*$product->offer->percentage)/100))>0 ? ($product->price-(($product->price*$product->offer->percentage)/100)): 0), 3, '.', '') : number_format((float)($product->price ), 3, '.', '')}}{{__('website.kd_label')}}</strong>
                            @if($product->product_type == 2)
                            <small class="text-bold">{{ __('website.per_head_label')}}</small>
                                @endif
                        </div>
                        <div class="pull-right stock">
                            @if($product->in_stock ==1 && $product->quantity > 0 )
                                <span>({{ $product->quantity }}) {{ __('website.in_stock_label')}} </span>

                                @else

                                <span> {{ __('website.out_stock_label')}} </span>
                            @endif

                            <strong>{{ $product->code }}</strong>
                        </div>
                    </div>

                    @if($product->product_type == 2)
                        <p>{{ __('website.breed_label')}} : @foreach($product->breeds as $breed) {{ $breed->name }} @endforeach <br>
                            {{ __('website.gender_label')}}: @foreach($product->genders as $gender ) {{ $gender->name }} @endforeach <br>
                            {{ __('website.age_label')}}: @foreach($product->ages as $age ) {{$age->name}} @endforeach <br>
                            {{ __('website.color_label')}}: @foreach($product->colors as $color ) {{ $color->name }} @endforeach
                        </p>
                    @endif

                    <productdet
                        :product="{{ json_encode($productVue)}}" :productsizes="{{ json_encode($productVueSizes)}}"  ></productdet>




                </div><!--/.col-sm
          -6-->
            </div><!--/.row-->
        </div><!--/.product-details-->
    </div><!--/.innr-cont-area-->

    @include('includes.works');




@endsection
