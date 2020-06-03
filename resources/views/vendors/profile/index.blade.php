@extends('vendors.layouts.app') 
@section('title' , 'My Profile') 
@section('content')
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<div class="content-wrapper">
    <section class="content-header">
        <h1>
           My Profile
            {{--  <small>it all starts here</small>  --}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            {{--  <li><a href="#"></a></li>  --}}
            <li class="active"> My Profile</li>
        </ol>
    </section>
    @if(Session::has('success'))
    <div class="pad margin no-print">
        <div class="callout callout-success" style="margin-bottom: 0!important;">
            <h4><i class="fa fa-success"></i> Success:</h4>
            {{ Session::get('success') }}
        </div>
    </div>
    @endif
    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class=" col-md-12">
                    <!-- Horizontal Form -->
                    {{--
                    <div class="box box-info"> --}}
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Information</h3>
                        </div>
                        <form class="form-horizontal" action="{{ route('vendor.profile.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="box-body">
                                <div class="fields-group">
                                    <div class="col-sm-12">
                                        <label for="logo">logo 1</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <img height="200px" width="200px" class="img-responsive" src="{{ asset('/uploads/'.$user->logo) }}">
                                        <br />
                                        <input type="file" name="logo" />
                                        @if ($errors->has('logo'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('logo') }}</strong>
                                        </span> @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Vendor Email</label>
                                        <input type="text" value="{{ $user->email }}" class="form-control" name="email" id="email" placeholder="Enter Email ">
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span> @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Vendor Name (EN)</label>
                                        <input type="text" value="{{ $user->name }}" class="form-control" name="name" id="name" placeholder="Enter name ">
                                        @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span> @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Vendor Name (AR)</label>
                                        <input type="text" value="{{ $user->name_ar }}" class="form-control" name="name_ar" id="name_ar" placeholder="Enter name">
                                        @if ($errors->has('name_ar'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name_ar') }}</strong>
                                        </span> @endif
                                    </div>
                                    @if($user->parent_id ==0)
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Code</label>
                                        <input type="text" value="{{ $user->code }}" class="form-control" name="code" id="code" placeholder="Enter code">
                                        @if ($errors->has('code'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('code') }}</strong>
                                        </span> @endif
                                    </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Countries</label>
                                            <select class="form-control" name="country_id" id="country_id">
                                                <option  value="">Please select</option>
                                                @foreach($countries  as $country)
                                                <option value="{{ $country->id }}" @if($country->id == $user->country_id) selected @endif>{{ $country->name_en }}</option>
                                                    @endforeach
                                            </select>
                                             @if ($errors->has('country_id'))
                                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('country id','country ',$errors->first('country_id')) }}</strong>
                                        </span> @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Governorate</label>
                                            <select class="form-control" name="governorate_id" id="governorate_id">
                                                <option  value="">Please select</option>
                                                @foreach($governorates  as $governorate)
                                                    <option value="{{ $governorate->id }}" @if($governorate->id == $user->governorate_id) selected @endif>{{ $governorate->name_en }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('governorate_id'))
                                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('governorate id','governorate ',$errors->first('governorate_id')) }}</strong>
                                        </span> @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Addresss</label>
                                            <input type="text"   class="form-control" name="address" id="address" placeholder="address" value="{{ $user->address }}">
                                            @if ($errors->has('address'))
                                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span> @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="location_map" class="col-sm-2 control-label">{{ __('location map')}}</label>
                                            <div class="col-sm-10">
                                                <div id="map" style="height: 300px;"></div>
                                                <input name="latitude" type="hidden" id="latitude" value="{{$user->latitude}}">
                                                <input name="longitude" type="hidden" id="longitude" value="{{$user->longitude}}">
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Phone</label>
                                            <input type="number" style="-webkit-appearance: none;    -moz-appearance: textfield;" min="0"  class="form-control" name="phone" id="phone" placeholder="phone" value="{{ $user->phone }}">
                                            @if ($errors->has('phone'))
                                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span> @endif
                                        </div>


                                    @endif
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Password</label>
                                        <input type="password"   class="form-control" name="password" id="password" placeholder="Password">
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span> @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Vendor Overview (EN)</label>
                                        <textarea class="form-control" name="overview_en" id="overview_en" rows="5" placeholder="Enter ...">{{ $user->overview_en }}</textarea>
                                        @if ($errors->has('overview_en'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('overview_en') }}</strong>
                                        </span> @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Vendor Overview (AR)</label>
                                        <textarea class="form-control" name="overview_ar" id="overview_ar" rows="5" placeholder="Enter ...">{{ $user->overview_ar }}</textarea>                                        {{-- <input type="" value="{{ $user->overview_ar }}" class="form-control" placeholder="Enter email">                                        --}}
                                        @if ($errors->has('overview_ar'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('overview_ar') }}</strong>
                                        </span> @endif
                                    </div>



                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right">Save</button>
                            </div>
                        </form>
                    </div>
                    {{-- </div> --}}

            </div>
        </div>


</div>
</section>
@endsection
 
@section('lower_javascript')
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&sensor=false&callback=initMap"></script>
    <script  src="https://goldencala.com/map.js"></script>
@endsection
