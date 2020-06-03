@extends('vendors.layouts.app') 
@section('title' , 'Payment') 
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Payment

        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            {{--
            <li><a href="#"></a></li> --}}
            <li class="active">Payment</li>
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
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        {{--
                        <h3 class="box-title">Hover Data Table</h3> --}}
                    </div>
                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Bank Details :</h3>
                                </div>
                                <form class="form-horizontal" method="post" action="{{ route('vendor.payment.change_bank_info') }}">
                                    @csrf
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="account_name" class="col-sm-2 control-label">Account Name</label>

                                            <div class="col-sm-10">
                                                <input type="text" value="{{ $bankInfo ? $bankInfo->account_name : '' }}" class="form-control" name="account_name" id="account_name"
                                                    placeholder="Account Name">                                                @if ($errors->has('account_name'))
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('account_name') }}</strong>
                                                    </span> @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="address" class="col-sm-2 control-label">Address </label>

                                            <div class="col-sm-10">
                                                <input type="text" value="{{ $bankInfo ? $bankInfo->address : '' }}" class="form-control" name="address" id="address" placeholder="Address">                                                @if ($errors->has('address'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('address') }}</strong>
                                                </span> @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="bank_name" class="col-sm-2 control-label">Bank Name </label>

                                            <div class="col-sm-10">
                                                <select class="form-control" name="bank_id" id="bank_id">
                                                    @foreach($banks as $bank)
                                                        <option value="{{ $bank->id }}" @if($bank->id == $bankInfo->bank_id) selected @endif>{{ $bank->title }}</option>
                                                        @endforeach
                                                </select>

                                                @if ($errors->has('bank_name'))
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('bank_name') }}</strong>
                                                    </span> @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="IBAN" class="col-sm-2 control-label">IBAN </label>

                                            <div class="col-sm-10">
                                                <input type="text" value="{{ $bankInfo ? $bankInfo->iban : '' }}" class="form-control" name="iban" id="iban" placeholder="IBAN">                                                @if ($errors->has('iban'))
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('iban') }}</strong>
                                            </span> @endif </div>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        {{-- <button type="submit" class="btn btn-default">Cancel</button> --}}
                                        <button type="submit" class="btn btn-info pull-right">Save Bank Information</button>
                                    </div>
                                </form>
                            </div>

                        </div>

                    </div>
                </div>
    </section>
    </div>
@endsection
 
@section('lower_javascript')
    <link rel="stylesheet" href="{{ asset('vendor_assets2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <script src="{{ asset('vendor_assets2/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script>
        $(function () {
      $('#example1').DataTable()
      $('#example2').DataTable({
        'paging'      : true,
        'lengthChange': false,
        'searching'   : false,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false
      })
    })
    </script>
@endsection
