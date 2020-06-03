
@extends('layout.app')
@section('content')
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ route('home') }}">{{__('website.home_label')}}</a></li>

        </ul>
    </div>



        <div class="get-touch">
            <p>{{__('website.please_fill_out_label')}}</p>
            <div class="row">
                <form action="{{ route('website.product.chooseProduct') }}" method="post">
                    @csrf
                    <div class="col-sm-4 mt-10 mb-10">
                        <select class="form-control" placeholder="{{__('website.name_req_label')}}" name="order_requester" required>
                            <option value="" >مقدم الطلب</option>
                            @foreach($orderRequester as $des)
                                <option value="{{ $des->id }}">ا{{ $des->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4 mt-10 mb-10">
                        <select class="form-control" placeholder="{{__('website.name_req_label')}}" name="order_destination" required>
                            <option value="">الطلب خاص ب </option>
                            @foreach($orderDes as $des)
                                <option value="{{ $des->id }}">ا{{ $des->title }}</option>
                            @endforeach
                        </select>                    </div>
                    <div class="col-sm-4 mt-10 mb-10">
                        <select class="form-control" placeholder="{{__('website.name_req_label')}}" name="order_category" required>
                            <option value="" >التصنيف </option>
                            @foreach($orderCats as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name_en }}</option>
                            @endforeach
                        </select>                     </div>
                    <div class="col-sm-12 mt-10 mb-10">
                        <textarea class="form-control" placeholder="{{__('website.message_req_label')}}" name="order_extra" required></textarea>
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
