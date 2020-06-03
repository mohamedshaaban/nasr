
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title> Vendor - @yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('vendor_assets/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ asset('vendor_assets/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{ asset('vendor_assets/bower_components/Ionicons/css/ionicons.min.css')}}">
  <link rel="stylesheet"
        href="{{ asset('vendor_assets/bower_components/bootstrap-daterangepicker/daterangepicker.css')}} ">
  <link rel="stylesheet"
        href="{{ asset('vendor_assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">

  <link rel="stylesheet" href="{{ asset('vendor_assets/plugins/iCheck/all.css')}}">
  <link rel="stylesheet" href="{{ asset('vendor_assets/bower_components/select2/dist/css/select2.min.css')}}">
  <link rel="stylesheet"
        href="{{ asset('vendor_assets/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}">
  <link rel="stylesheet" href="{{ asset('vendor_assets/plugins/timepicker/bootstrap-timepicker.min.css')}}">

  <link rel="stylesheet" href="{{ asset('vendor_assets/dist/css/skins/_all-skins.min.css')}}">
  <link rel="stylesheet" href="{{ asset('vendor_assets/dist/css/AdminLTE.min.css')}}">
  <link rel="stylesheet" href="{{ asset('vendor_assets/dist/css/skins/skin-blue.min.css')}}">
  <link rel="stylesheet" href="{{ asset('vendor_assets/plugins/pace/pace.min.css')}}">
  <link rel="stylesheet" href="{{ asset('vendor_assets/dist/css/custom.css')}}">

@yield('custom_css')
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  @include('vendors.includes.header')
  <div id="app">
    @include('vendors.includes.sidebar')
    @yield('content')
  </div>
  @include('vendors.includes.footer')
  @include('vendors.includes.sidebar_content')
</div>
<script src="{{ asset('vendor_assets/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('vendor_assets/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('vendor_assets/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ asset('vendor_assets/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('vendor_assets/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('vendor_assets/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
<script src="{{ asset('vendor_assets/bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{ asset('vendor_assets/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script
    src="{{ asset('vendor_assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script
    src="{{ asset('vendor_assets/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{ asset('vendor_assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{ asset('vendor_assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{ asset('vendor_assets/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('vendor_assets/bower_components/fastclick/lib/fastclick.js')}}"></script>
<script src="{{ asset('vendor_assets/dist/js/adminlte.min.js')}}"></script>
<script src="{{ asset('vendor_assets/bower_components/PACE/pace.min.js')}}"></script>
<script src="{{ asset('vendor_assets/custom.js')}}"></script>
<script src="{{ asset('js/app.js')}}"></script>

@yield('lower_javascript')



</body>
</html>
