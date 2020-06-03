@extends('layout.app')
@section('content')


    @if($settings->banner_product_details)

        <section class="sec-banner">
            <div class="banner">

                <img src="{{ asset('uploads/'.$settings->banner_product_details) }}" class="img-responsive"
                     alt="banner">

                <h2>{{ __("website.girl") }} <span class="text-uppercase">{{ __("website.Clothing") }}</span></h2>
            </div>
        </section>
    @endif
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
                                <div class="rateit" data-rateit-mode="font" data-rateit-value="{{  $productRate }}" data-rateit-ispreset="true" data-rateit-readonly="true"></div>
                            </div>
                        </div>
                        <div class="reviews col-sm-6 col-mmd-7">
                            <a onclick="$('[href=#Reviews]').tab('show')"
                               href="#divReviews"><span class="red">{{ count($product->reviews) }}</span> {{ __('website.reviews_label')}}</a> <a
                                class=""
                                onclick="$('[href=#Reviews]').tab('show')"
                                href="#divReviews">{{ __('website.add_review_label')}}</a>
                        </div>
                    </div><!--/.row-->
                    <div class="price clearfix">
                        <div class="pull-left">
                            @if($product->offer)
                            <strong class="old" id="prodoldprice">{{ number_format((float)($product->price), 2, '.', '') }}{{__('website.kd_label')}}</strong>
                            @endif
                            <strong id="prodPrice">{{ $product->offer ? $product->offer->is_fixed ? number_format((float)($product->price-($product->offer->fixed)), 2, '.', '') : number_format((float)($product->price-(($product->price*$product->offer->percentage)/100)), 2, '.', '') : number_format((float)($product->price ), 2, '.', '')}}{{__('website.kd_label')}}</strong>
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
<div id="divReviews">
            <div class="tab-cvr">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#Details" aria-controls="home" role="tab"
                                                              data-toggle="tab">{{ __('website.details_label')}}</a></li>

                    <li role="presentation"><a href="#Reviews" aria-controls="messages" role="tab"
                                               data-toggle="tab">{{ __('website.reviews_label')}}  {{  (count($product->reviews)) }}</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="Details">
                        <p>
                            {{ $product->description }}
                        </p>

                    </div>

                    <div role="tabpanel" class="tab-pane" id="Reviews">
                        @foreach ($product->reviews as $review)
                            <section>
                                <h4>{{ $review->user->name }}</h4>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="col-xs-4 col-sm-4">
                                                <p class="date-rate">{{ __('website.rating_label')}}</p>
                                            </div>
                                            <div class="col-xs-8 col-sm-8">

                                                <div id="tab-rate" class="rateit" data-rateit-mode="font" data-rateit-value="{{ $review->rate }}" data-rateit-ispreset="true" data-rateit-readonly="true"></div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <p>{{ $review->comment }}</p>
                                    </div>
                                    <div class="col-sm-12">

                                    </div>
                                </div><!--/.row-->
                            </section><!--/section-->
                        @endforeach


                        <hr>
                        @if(Auth::user())
                        <section class="ur-reviewing">
                            {{--<input type="range" value="0" step="1" id="backing5">--}}

                            <span>{{ __('website.your_reviewing_label')}}</span>
                            <h3>{{ $product->name }}</h3>
                            <strong>{{ __('website.your_rating_label')}} <span class="red">*</span></strong><br>
                            {{--<strong class="mt-10">{{ __('website.rating_label')}}</strong><br>--}}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="rate-xl mt-10">
                                        <div class="col-xs-8 col-sm-8">
                                            <div id="rateit7" data-rateit-resetable="false"></div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form class="mt-20 row reviewfield " action="{{ route('website.product.addReview') }}"
                                  method="post">
                                @csrf
                                <input type="hidden" name="productID" value="{{ $product->id }}">

                                <input type="hidden" id="backing7" name="rate">
                                <div class="col-sm-6">
                                    <div class="mt-20">
                                        <label>{{ __('website.nickname_label')}} <span class="red">*</span></label>
                                        <input type="" class="form-control" placeholder="{{ __('website.nickname_label')}}*" disabled=""
                                               name="nickname" value="{{ Auth::user()->name }}">
                                    </div>

                                    <div class="mt-20">
                                        <label>{{ __('website.add_review_label')}} <span class="red">*</span></label>
                                        <textarea class="form-control" placeholder="{{ __('website.add_review_label')}}*" name="comment"
                                                  required></textarea>
                                    </div>
                                    <div class="mt-20">
                                        <button class="btn-lg btn-primary rounded-0" type="submit">{{ __('website.submit_label')}}</button>
                                    </div>
                                </div>
                            </form>
                        </section>
                        @else
                                <section class="ur-reviewing">
                                    {{--<input type="range" value="0" step="1" id="backing5">--}}

                                    <span>{{ __('website.you_have_label')}} <a class="reviewLogin" href="{{ route('website.login') }}">{{ __('website.login_here_lavel')}}</a> {{ __('website.add_your_review_label')}}</span>
                                </section>
                            @endif

                    </div>
                </div>
            </div>
</div>
        </div><!--/.product-details-->
    </div><!--/.innr-cont-area-->

    @include('includes.works');




@endsection
