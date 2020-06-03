
@extends('layout.app')
@section('content')

<div class="container innr-cont-area">
    <div class="row">


        @include('customer.includes.profile_menu')
        <div class="col-sm-9 right-sec my-account my-order mt-30">
            <div class="heading">{{ __('website.menu_account_info_label')}}</div>
<form action="{{ route('customer.update_profile') }}" method="post">
    @csrf
            <div class="get-touch">
                <h5>{{ __('website.update_personal_info_label')}}</h5>

                <div class="row">
                    <div class="col-sm-6 mt-10 mb-10">
                        <input type="text" class="form-control" placeholder="{{ __('website.first_name_label')}}*" value="{{ $user->first_name }}" name="first_name" >
                    </div>
                    <div class="col-sm-6 mt-10 mb-10">
                        <input type="text" class="form-control" placeholder="{{ __('website.last_name_label')}}*" value="{{ $user->last_name }}" name="last_name" required>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-6 mt-10 mb-10">
                        <input type="text" class="form-control" placeholder="{{ __('website.mobile_label')}}*" value="{{ $user->mobile }}" name="mobile" >
                    </div>
                   

                </div>
            </div>

            <div class="get-touch">
                <h5>{{ __('website.change_email_password_label')}}</h5>

                <div class="row">
                    <div class="col-sm-6 mt-10 mb-10  {{ $errors->has('email') ? 'has-error' : ''}}">
                        <input type="email" required value="{{ $user->email }}" class="form-control" placeholder="{{ __('website.email_req_label')}}" name="email">
                     </div>
                    <div class="col-sm-6 mt-10 mb-10 {{ $errors->has('old_password') ? 'has-error' : ''}}">
                        <input type="password" class="form-control" placeholder="{{ __('website.current_password_label')}}" name="old_password">
                        {!! $errors->first('old_password', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="col-sm-6 mt-10 mb-10 {{ $errors->has('password') ? 'has-error' : ''}}">
                        <input type="password" class="form-control" placeholder="{{ __('website.new_password_label')}}" name="password">
                        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="col-sm-6 mt-10 mb-10 {{ $errors->has('password_confirmation') ? 'has-error' : ''}}">
                        <input type="password" class="form-control" placeholder="{{ __('website.Confirm_Password_label')}}" name="password_confirmation">
                        {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
                    </div>

                    {{--<div class="col-sm-3 mt-10 text-center">--}}
                        {{--<button class="btn-lg btn-primary rounded-0">{{ __('website.edit_label')}}</button>--}}
                    {{--</div>--}}
                    <div class="col-sm-3 mt-10 text-center">
                        <button class="btn-lg btn-primary rounded-0" type="submit">{{ __('website.save_label')}}</button>
                    </div>
                </div>
            </div>


</form>





        </div><!--/.col-sm-9-->
    </div>

</div><!--/.innr-cont-area-->
@include('includes.works');

@endsection
