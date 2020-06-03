@extends('vendors.layouts.app')
@section('title' , 'Edit Commission')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Commission Managment
                {{--  <small>advanced tables</small>  --}}
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ route('vendor.commsiision.index') }}">Commission Managment</a></li>
                <li class="active">Create</li>
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
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Offer</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>                    {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>                    --}}
                    </div>
                </div>
                <div class="box-body">
                    <form class="form-horizontal" method="post" action="{{ route('vendor.commission.store'  )}}">
                        <input type="hidden" name="commissionId" value="{{ $commission->id }}" />
                        @csrf

                            <div class="form-group">
                                <label for="percentage" class="col-sm-2 control-label">Fixed</label>
                                <div class="col-sm-10">
                                    <input type="number" name="fixed" min="1" max="100" value="{{ $commission->fixed }}" class="form-control" id="fixed" placeholder="Fixed">
                                    @if ($errors->has('fixed'))
                                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('fixed') }}</strong>
                                </span> @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="percentage" class="col-sm-2 control-label">Precentage</label>
                                <div class="col-sm-10">
                                    <input type="number" name="precentage" min="1" max="100" value="{{ $commission->precentage }}" class="form-control" id="precentage" placeholder="Precentage">
                                    @if ($errors->has('precentage'))
                                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('precentage') }}</strong>
                                </span> @endif
                                </div>
                            </div>


                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('lower_javascript')
@endsection
