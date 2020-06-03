<header class="clearfix">
    <div class="full-width">
        <div class="container">
            <div class="row nav-section">
                <div class="col-lg-1 col-sm-2 clearfix">
                    <a class="navbrand" href="{{ route('home') }}">
                        <img src="/images/logo.png" style="max-width: none;" alt="logo" class="mCS_img_loaded" />
                    </a><!-- /.logo -->
                </div><!-- /.col-lg-10 -->
                <div class="col-lg-6 col-sm-12 clearfix">
                    <div class="navigation full-width">
                        <nav class="navbar navbar-default">
                            <div class="navbar-header">
                                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>

                            <!-- /.nav-collapse -->

                            <!-- /.search_box_cover -->
                        </nav>
                        <!-- /.navigation -->
                    </div>
                    <!-- /.col-lg-10 -->
                </div><!-- /.col-lg-11 -->
                <div class="col-lg-5 col-sm-12 top-sec clearfix">
                    <ul class="top-links">
                        @if(!Auth::user())
                        <li>
                            <a class="main" href="{{ route('website.register') }}">{{__('website.Register_label')}}</a>
                        </li><!--/li-->

                        <li>
                            <a class="main" href="{{ route('website.login') }}">{{__('website.Sign_in_lable')}}</a>
                        </li><!--/li-->
                        @else
                            <li>
                            <a class="main" href="{{ route('customer.dashboard') }}">{{ Auth::user()->name }}</a>
                            </li>
                            <li>
                                <a class="main" href="{{ route('customer.logout') }}">{{__('website.logout')}}</a>
                            </li>
                            @endif
                        <li>
                            @if(Auth::user())
                            <a class="main" href="{{ route('customer.wish-list') }}"><img src="/images/wishlist.png" alt=""></a>
                                @else
                                <a class="main" href="{{ route('website.login') }}"><img src="/images/wishlist.png" alt=""></a>
                            @endif
                        </li><!--/li-->
                        <li>
                            <a class="main" href="#" id="cartEnable"><img src="/images/cart.png" alt=""><span class="">
                        <cart-count /></span></a>
                            <div class="cart_box" id="cart_bx">
                                @include('includes.cart')
                                <!-- /.cart_contents -->
                                {{--<a href="#" class="button-crt" type="button">VIEW SHOPPING BAG</a>--}}
                            </div><!--/.cart_box-->
                        </li><!--/li-->
                        <li>
                            <a class="main" href="#search">
                                <span class=""><img src="/images/search.png" alt=""></span>
                            </a>
                        </li><!--/li-->

                    </ul>
                </div>
            </div><!-- /.row -->
        </div><!--/.container-->
    </div>

    <!--Seaarch Pop up-->
    <div id="search">
        <button type="button" class="close">×</button>
        <form action="{{ route('website.search') }}" method="get">
            <input type="search" name="qt" value="" placeholder="{{__('website.type_words_label')}}" />
            <button type="submit" class="btn btnlg">{{__('website.search_label')}}</button>
        </form>
    </div>
</header><!--/header-->
<div class="innr-banner fullwidth">
    <div class="container">

    </div>
</div><!--/.banner-->
<body>


