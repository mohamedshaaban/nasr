@extends('layout.app')
@section('content')

    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ route('home') }}">{{__('website.home_label')}}</a></li>
            <li class="active">{{__('website.customer_login_label')}}</li>
        </ul>
    </div>
    <div class="container innr-cont-area login-page">
        <h3 class="innerpage-head">{{ __('website.customer_login_label') }}</h3>
        <div class="row">
            <div class="col-sm-6">
                <h4>{{ __('website.registered_customers_lable') }}</h4>

                <form action="{{ route('website.submit.login') }}" method="post">
                    @csrf
                <div class="row">
                    {{--{{ dd($errors) }}--}}
                    @if ($errors->has('error'))
                        <span class= "errorlogin alert alert-danger" role="alert">
                            <strong>{{ $errors->first('error') }}</strong>
                        </span>
                    @endif
                    <div class="col-sm-11 mt-10 mb-10">
                        <label>{{ __('website.email_req_label') }}</label>
                        <input type="email" class="form-control" placeholder="" name="email" required>
                    </div>
                    <div class="col-sm-11 mt-10 mb-10">
                        <label>{{ __('website.password_label') }}*</label>
                        <input type="password" class="form-control" placeholder="" name="password" required>
                    </div>

                    <div class="col-sm-11 mt-30">
                        <div class="inline-block">

                            <button class="btn-lg btn-primary rounded-0" type="submit">{{ __('website.Sign_in_lable') }}</button> </div>
                    </div>
                </div>
                </form>
            </div>


        </div>

    </div><!--/.innr-cont-area-->

    @include('includes.works');

@endsection
