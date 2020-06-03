
@extends('layout.app')
@section('content')
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ route('home') }}">{{__('website.home_label')}}</a></li>
            <li class="active">{{ $page->name }}</li>
        </ul>
    </div>

    <div class="container innr-cont-area">
        <div class="row">
            <div class="col-sm-12 about-us">
                {!! $page->description !!}
            </div>
        </div>
    </div><!--/.innr-cont-area-->

@include('includes.works')
@endsection
