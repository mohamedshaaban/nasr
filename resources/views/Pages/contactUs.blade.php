
@extends('layout.app')
@section('content')
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ route('home') }}">{{__('website.home_label')}}</a></li>
            <li class="active">{{__('website.contact_us_label')}}</li>
        </ul>
    </div>

    <div class="container innr-cont-area contact-us">
        <div class="address">
            <h3>{{__('website.bastaat_label')}}</h3>
            <span class="data">
          {!!  $settings->location  !!}<br>

        </span>
            <div class="contact">
                <span><small>{{__('website.call_label')}}: </small>{{ $settings->phone }}</span>
                <span><small>{{__('website.fax_label')}}: </small>{{ $settings->fax }}</span>
                <span><small>{{__('website.email_label')}}: </small>{{ $settings->email_info }}</span>
            </div>
        </div>

        <div class="get-touch">
            <h3>{{__('website.get_in_touch_label')}}</h3>
            <p>{{__('website.please_fill_out_label')}}</p>
            <div class="row">
                <form action="{{ route('sendContactUs') }}" method="post">
                    @csrf
                <div class="col-sm-4 mt-10 mb-10">
                    <input type="text" class="form-control" placeholder="{{__('website.name_req_label')}}" name="name" required>
                </div>
                <div class="col-sm-4 mt-10 mb-10">
                    <input type="email" class="form-control" placeholder="{{__('website.email_req_label')}}" name="email" required>
                </div>
                <div class="col-sm-4 mt-10 mb-10">
                    <input type="number" class="form-control" placeholder="{{__('website.mobile_req_label')}}" name="mobile" required>
                </div>
                <div class="col-sm-12 mt-10 mb-10">
                    <textarea class="form-control" placeholder="{{__('website.message_req_label')}}" name="message" required></textarea>
                </div>
                <div class="col-sm-12 mt-30 text-center">
                    <button class="btn-lg btn-primary rounded-0" type="submit">{{__('website.submit_label')}}</button>
                </div>
                </form>
            </div>
        </div>
    </div><!--/.innr-cont-area-->


    @include('includes.works')
@endsection
