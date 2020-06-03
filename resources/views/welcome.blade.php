@extends('layout.app')
@section('content')
    <div class="banner fullwidth">
        <ul class="bannerSlider">
            @foreach($sliders as $slider)

                <li class="slide">
                    <a href="#">
                        <div class="container slide-text">

                            {!! $slider->title  !!}
                        </div>
                        <div class="slide-image">
                            <img style=" width: 100%;" src="{{ asset('uploads/'.$slider->image) }}" alt="" />
                        </div>
                    </a>
                </li><!-- 2. slide -->
            @endforeach


        </ul>
    </div><!--/.banner-->

    <div class="top-category fullwidth">
        <div class="container">
            <h4>{{__('website.top_categories_label')}}</h4>
            <ul class="categories-slide">
                @foreach($topCategories as $category)
                    <li class="slide">
                        <a href="/products?categories={{ $category->id }}" class="cat-box">
                            <span class="img"><img src="{{ asset('uploads/'.$category->image) }}"></span>
                            <span>{{ $category->name }}</span>
                        </a>
                    </li><!--/li-->
                @endforeach
            </ul><!--/ul-->
        </div><!--/.container-->
    </div><!--/.best-sellers-->

    <div class="special-offers container">

        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#spl-offr" aria-controls="spl-offr" role="tab" data-toggle="tab">{{__('website.special_offers_label')}}</a></li>
            <li role="presentation"><a href="#new-arrvl" aria-controls="new-arrvl" role="tab" data-toggle="tab">{{__('website.new_arrivals_label')}}</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="spl-offr">
                <ul class="sploffer-slide">
                    @foreach($specialOffers as $product)
                        <li class="slide">
                            <div class="offrbox">
                               <a href="{{ route('website.product.show' ,$product->slug_name) }}">
                                <div class="img">
                                    <img src="{{ asset('uploads/'.$product->main_image) }}">
                                </div>
                                <h5>@if($product->categories) @foreach ($product->categories as $category ) {{  $category->name }} @endforeach @endif</h5>
                                <h3>{{ $product->name }}</h3>
                                
                                @if($product->offer)
                                    <div class="price">

                                        <div class="old">{{__('website.kd_label')}} {{  number_format((float)($product->price), 3, '.', '') }}</div>
                                        @if( $product->offer->is_fixed==1)
                                            <div class="new"  >{{__('website.kd_label')}} {{  number_format((float)( $product->price-$product->offer->fixed), 3, '.', '') }}</div>
                                        @else
                                            <div class="new"  >{{__('website.kd_label')}} {{  number_format((float)($product->price -(( $product->price* $product->offer->percentage)/100)), 3, '.', '') }}</div>
                                        @endif
                                    </div>
                                @else
                                    <div class="price" >
                                        <div class="new"  >{{__('website.kd_label')}} {{  number_format((float)($product->price), 3, '.', '') }}</div>
                                        @endif
                                    </div>
                               </a>

                        </li><!--/li-->
                    @endforeach
                </ul><!--/ul-->
                <div class="text-center fullwidth"><a href="{{ route('website.product.offers') }}" class="view-all">{{__('website.list_all_label')}}</a></div>
            </div>
            <div role="tabpanel" class="tab-pane" id="new-arrvl">
                <ul class="new-arrvl-slide">
                    @foreach($newProducts as $product)
                        <li class="slide">
                            <a href="{{ route('website.product.show' ,$product->slug_name) }}">
                            <div class="offrbox">
                                <div class="img">
                                    <img src="{{ asset('uploads/'.$product->main_image) }}">
                                </div>
                                <h5>@if($product->categories) @foreach ($product->categories as $category ) {{  $category->name }} @endforeach @endif</h5>
                                <h3>{{ $product->name }}</h3>
                               
                                @if($product->offer)
                                    <div class="price">

                                        <div class="old">{{__('website.kd_label')}} {{ number_format((float)(  $product->price), 3, '.', '')  }}</div>
                                        @if( $product->offer->is_fixed==1)
                                            <div class="new"  >{{__('website.kd_label')}} {{  number_format((float)( $product->price-$product->offer->fixed), 3, '.', '') }}</div>
                                        @else
                                            <div class="new"  >{{__('website.kd_label')}} {{  number_format((float)($product->price -(( $product->price* $product->offer->percentage)/100)), 3, '.', '') }}</div>
                                        @endif
                                    </div>
                                @else
                                    <div class="price" >
                                        <div class="new"  >{{__('website.kd_label')}} {{ number_format((float)($product->price), 3, '.', '') }} </div>
                                        @endif
                                    </div>
                            </a>
                        </li><!--/li-->
                    @endforeach
                </ul><!--/ul-->
                <div class="text-center fullwidth"><a href="/products?new=new" class="view-all">{{__('website.list_all_label')}}</a></div>
            </div>
        </div>
    </div><!--/.special-offers-->

    <div class="container adverticement">
        @foreach($ads as $ad)

            <div class="col-sm-4">
                <a href="{{ $ad->link  }}"><img src="{{ asset('uploads/'.$ad->image) }}"></a>
            </div>
        @endforeach

    </div><!--Advertcement-->

    <div class="farmers-home">
        <div class="container">
            <div class="col-sm-11 col-sm-offset-1 mt-30 pl-50">
                @if($lang =='en')
                <h2>{{ $settings->framer_title_en }}</h2>
                <p>{{ $settings->framer_desc_en }}</p>
                @else 
                <h2>{{ $settings->framer_title_ar }}</h2>
                <p>{{ $settings->framer_desc_ar }}</p>                
                @endif
                <a class="more" href="{{ $settings->link }}">{{__('website.read_more_label')}}</a>
            </div>
        </div>
    </div>
    @include('includes.works');

@endsection
