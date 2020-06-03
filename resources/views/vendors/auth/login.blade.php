@extends('layout.app')
@section('content')

    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ route('home') }}">{{__('website.home_label')}}</a></li>
            <li class="active">{{ __('website.vendor_login_label')}}</li>
        </ul>
    </div>
    <div class="container innr-cont-area login-page">
        <h3 class="innerpage-head">{{ __('website.vendor_login_label')}}</h3>
        <div class="row">
            <div class="col-sm-6">
                <h4>{{ __('website.Registered Vendors')}}</h4>

                <form action="{{ route('vendor.submit.login') }}" method="post">
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
                    <div class="col-sm-11 mt-10">
                        <div class="inline-block">

                            <button class="btn-lg btn-primary rounded-0" type="submit">{{ __('website.Sign_in_lable') }}</button> </div>
                            <div class="inline-block "><a href="{{ route('vendor.password.request') }}" class="frgt-psswrd pl-10">{{ __('website.forgot_password_label') }}?</a></div>

                    </div>
                </div>
                   
                </form>
            </div>

            <div class="col-sm-6">
                <h4>{{ __('website.New Vendors')}}</h4>
                <div class="row">
                    <div class="col-sm-12 mt-10 mb-10">
                        {{ __('website.create_Account_benefits_label') }}
                        <div class="col-sm-12 mt-30">
                            <a href="{{ route('vendor.register') }}"> <button class="btn-lg btn-primary rounded-0">{{ __('website.create_account_label') }}</button></a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div><!--/.innr-cont-area-->

    @include('includes.works');

@endsection
