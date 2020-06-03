@extends('layout.app')


@section('content')
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ route('home') }}">{{__('website.home_label')}}</a></li>
            <li class="active">{{__('website.forgot_password_label')}}</li>
        </ul>
    </div>
    <div class="container innr-cont-area forgot-psswrd-page">
        <h3 class="innerpage-head">{{__('website.forgot_password_label')}}</h3>
        <hr>
    <div class="row justify-content-center">
        <div class="">
            <div class="card">


                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('vendor.password.request.email') }}">
                        @csrf
                        <div class="">
                            <span>{{__('website.please_enter_email_label')}}</span>
                            <br>
                        <div class="row">


                            <div class="col-sm-8 col-md-8 col-lg-6 mt-10 mb-10">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="  row  ">
                            <div class="col-sm-8 mt-20">
                                <div class="inline-block"><button class="btn-lg btn-primary rounded-0">

                                        {{__('website.Send_Password_Reset_Link_label')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
