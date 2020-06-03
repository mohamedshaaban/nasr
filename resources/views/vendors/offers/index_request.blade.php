@extends('vendors.layouts.app') 
@section('title' , 'Offer Managment') 
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Data Tables
            <small>advanced tables</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Tables</a></li>
            <li class="active">Data tables</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Hover Data Table</h3>
                    </div>
                    <div class="box-body">
                        @if(count($productOfferRequests)
                        < 1) <p>no products</p>
                            @else
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Percentage</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productOfferRequests as $productOfferRequest )
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $productOfferRequest->product->name}}</td>
                                        <td>{{ $productOfferRequest->value . '%' }}</td>
                                        <td> {{ $productOfferRequest->from}}</td>
                                        <td>{{ $productOfferRequest->to }}</td>
                                        <td>
                                            <span class="{{ $productOfferRequest->status_string['class'] }} ">
                                            {{ $productOfferRequest->status_string['name'] }} 
                                        </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                            <div class="box-footer clearfix">

                            </div>
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