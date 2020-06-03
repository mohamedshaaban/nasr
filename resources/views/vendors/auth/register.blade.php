@extends('layout.app')
@section('content')

    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ route('home') }}">{{__('website.home_label')}}</a></li>
            <li class="active">{{__('website.create_new_account_vendor_label')}}</li>
        </ul>
    </div>

    <div class="container innr-cont-area register-page">
        <h3 class="innerpage-head">{{__('website.create_new_account_vendor_label')}}</h3>
        <section class="row">
            <div class="col-sm-7">
                <h4>{{__('website.personal_info_label')}}</h4>
                <form  action="{{ route('vendor.submit.register') }}" method="post">
                    @csrf
                <div class="row">
                    <div class="col-sm-11 col-md-10 col-lg-9 mt-10 mb-10  {{ $errors->has('first_name') ? 'has-error' : ''}}">
                        <label>{{__('website.first_name_label')}}</label>
                        <input type="text" class="form-control" placeholder="" name="first_name" value="{{ old('first_name') }}" required>
                        {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="col-sm-11 col-md-10 col-lg-9 mt-10 mb-10 {{ $errors->has('last_name') ? 'has-error' : ''}}">
                        <label> {{__('website.last_name_label')}}</label>
                        <input type="text" class="form-control" placeholder="" name="last_name" value="{{ old('last_name') }}" required>
                        {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
                    </div>

                </div>
                <div class="get-touch">

                    <h4>{{__('website.sign_in_info_label')}}</h4>
                    <div class="row">
                        <div class="col-sm-11 col-md-10 col-lg-9 mt-10 mb-10 {{ $errors->has('email') ? 'has-error' : ''}}" id="classerrorEmail">
                            <label>{{__('website.email_req_label')}}</label>
                            <input type="email" class="form-control" placeholder="" required name="email" value="{{ old('email') }}" >
                            <div id="errorEmail">
                            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-11 col-md-10 col-lg-9 mt-10 mb-10 {{ $errors->has('password') ? 'has-error' : ''}}">
                            <label> {{__('website.password_label')}}*</label>
                            <input type="password" class="form-control" placeholder="" required name="password" value="{{ old('password') }}">
                            {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="col-sm-11 col-md-10 col-lg-9 mt-10 mb-10 {{ $errors->has('password_confirmation') ? 'has-error' : ''}}">
                            <label> {{__('website.Confirm_Password_label')}}*</label>
                            <input type="password" class="form-control" placeholder="" required value="{{ old('password_confirmation') }}" name="password_confirmation">
                            {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}

                        </div>
                         <div class="col-sm-12 mt-30 {{ $errors->has('g-recaptcha-response') ? 'has-error' : ''}}">
                                   <div class="g-recaptcha" data-sitekey="6LfqcLwUAAAAAI7scBxV4lPNKZJetLDlJGwXhpMY"></div>
                                   {!! $errors->first('g-recaptcha-response', '<p class="help-block">:message</p>') !!}

                             </div>
                        <div class="col-sm-11 col-md-10 col-lg-9 mt-10 mb-10 {{ $errors->has('password_confirmation') ? 'has-error' : ''}}">
                        <label for="pre"> <input type="checkbox" value="1" required="required" name="accept_terms">  {{__('website.agree_label')}} <a href="{{ route('pageDetails', ['slug'=>'terms']) }}" target="_blank" style="text-decoration: underline;">{{__('website.terms_conditions_label')}}</a> </label>
                       
                        
                         </div>
                        
                        <div class="col-sm-12 mt-30">
                            <button class="btn-lg btn-primary rounded-0" type="submit">{{__('website.create_account_label')}}</button>
                        </div>

                    </div>

                </div>
                </form>
            </div>
        </section>



    </div><!--/.innr-cont-area-->

    @include('includes.works');
@endsection
