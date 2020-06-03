@extends('vendors.layouts.app')
@section('title' , 'My Profile')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Create  user
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

                        <form class="form-horizontal" action="{{ route('vendor.user.store')}}" method="post" role="form" enctype="multipart/form-data">

                            @if(!$create)
                                <input type="hidden" name="vendor_id" id="vendor_id" value="{{ $user->id }}" />

                                @endif
                            @csrf
                            <div class="box-body">
                                <div class="fields-group">
                                    <div class="col-sm-12">
                                        <label for="logo">logo </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                                        <input type="text" value="{{ $user->name }}" class="form-control" name="name_en" id="name_en" placeholder="Enter name ">
                                        @if ($errors->has('name_en'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name_en') }}</strong>
                                        </span> @endif
                                    </div>

                                        <input type="hidden" value="{{ $user->name }}" class="form-control" name="name_ar" id="name_ar" placeholder="Enter name">


                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Password</label>
                                        <input type="password"   class="form-control" name="password" id="password" placeholder="Password">
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span> @endif
                                    </div>
@if(Auth::user()->parent_id ==0)
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Status</label>
                                        <div class="radio">
                                            <label>
                                                <input type="radio"  @if($user->is_active == 1) checked @endif   name="is_active" id="is_active"  value="1">
                                                Active
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio"  @if($user->is_active == 0) checked @endif  name="is_active" id="is_active"  value="0">
                                                Blocked
                                            </label>
                                        </div>

                                        @if ($errors->has('is_active'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('is_active') }}</strong>
                                        </span> @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Role</label>
                                        <div class="radio">
                                            <label>
                                                <input type="radio"  @if($user->permission == \App\Models\Vendors::VENDOR_FULL_ACCESS) checked @endif   name="permission" id="permission"  value="{{  \App\Models\Vendors::VENDOR_FULL_ACCESS }}">
                                                Full Access
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio"  @if($user->permission == \App\Models\Vendors::VENDOR_PRODUCT_ACCESS) checked @endif  name="permission" id="permission"  value="{{  \App\Models\Vendors::VENDOR_PRODUCT_ACCESS }}">
                                                Product Role
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio"  @if($user->permission == \App\Models\Vendors::VENDOR_SALES_ACCESS) checked @endif  name="permission" id="permission"  value="{{  \App\Models\Vendors::VENDOR_SALES_ACCESS }}">
                                                Sales Role
                                            </label>
                                        </div>
                                        @if ($errors->has('is_active'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('is_active') }}</strong>
                                        </span> @endif
                                    </div>
                                    @endif




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
@endsection
