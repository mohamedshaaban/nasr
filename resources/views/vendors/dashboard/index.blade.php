@extends('vendors.layouts.app')
@section('title' , 'Dashboard')
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('vendor_assets2/calendar/calendar.css')}}">
@endsection

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                DashBoard
                {{--  <small>it all starts here</small>  --}}
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                {{--  <li><a href="#"></a></li>  --}}

            </ol>
        </section>
        <section class="content">
            @if (session('status'))
                <div class="alert alert-danger">
                    {{ session('status') }}
                </div>
            @endif
                <div class="row">
                    <div class="col-lg-4 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{ $orders }}</h3>

                                <p>New Orders</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>

                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>{{ $products }}</h3>

                                <p>Products</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>

                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3>{{ $users }}</h3>

                                <p>Users</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>

                        </div>
                    </div>
                    <!-- ./col -->

                    <!-- ./col -->
                </div>
        </section>

    </div>

@endsection

@section('lower_javascript')
    <link href="{{ asset('vendor_assets2/calendar/fullcalendar.min.css') }}" rel='stylesheet' />
    <link href="{{ asset('vendor_assets2/calendar/fullcalendar.print.min.css') }}" rel='stylesheet' media='print' />
    <script src="{{ asset('vendor_assets2/calendar/fullcalendar.min.js') }} "></script>
@endsection
