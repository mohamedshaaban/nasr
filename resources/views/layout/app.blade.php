<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <title>{{ __('website.Welcome_to_Bastaat')}}</title>

    
    <meta name="keywords" content="بسطات">
    @if(\Request::route()->getName() == 'home')
    <meta name="description" content="بسطات هو سوق لعرض و بيع المنتجات الزراعية الكويتية في الكويت. يمكنك بسطات من شراء منتجات المزارع المحلية الكويتية. تسوق مع بسطات و أنت في المنزل">
@endif
 

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <h1 style="display:none">{{ __('website.Welcome_to_Bastaat')}}</h1>
    <link id="" rel="shortcut icon" href="/images/favicon.ico?" />


    <!-- Google Font face -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i|Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
    <!-- Bootstrap -->
    @if(app()->getLocale()=='ar')
        <link rel="stylesheet" href="{{ asset('css/bootstrap_ar.css')}}">
        @else
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css')}}">
        @endif

    <!-- SLick Slider -->
    <link rel="stylesheet" href="{{ asset('css/slick.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css')}}"/>
    <!-- Font face -->
    <link rel="stylesheet" href="{{ asset('css/fonts.css')}}" />
    <!-- custom input -->
    <link rel="stylesheet" href="{{ asset('css/custom-input.css')}}" />
@if(app()->getLocale()=='ar')
    <!-- custom style -->
        <link rel="stylesheet" href="{{ asset('css/style_ar.css')}}" />
    @else
    <link rel="stylesheet" href="{{ asset('css/style.css')}}" />
@endif
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css')}}" />

    <link rel="stylesheet" href="{{ asset('css/rateit.css')}}">




    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script>
        window.auth_user  = {!! json_encode(Auth::check()); !!};
    </script>
</head>



<div id="app">
    <div class="loading-holder">
        <div id="loading"></div>
    </div>
    @include('includes.header')
  @yield('content')


  @include('includes.footer')
</div>
<script src="https://code.jquery.com/jquery.min.js"></script>
<script src="{{ asset('js/jquery.rateit.js')}}"></script>
<script src="{{ asset('js/bootstrap.min.js')}}"></script>
<script src="{{ asset('js/slick.js')}}"></script>
<script src="{{ asset('js/app.js')}}"></script>

<script src="{{ asset('js/custom.js')}}"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>


@include('includes.js')


</html>
