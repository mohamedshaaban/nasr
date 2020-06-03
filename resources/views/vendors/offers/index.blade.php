@extends('vendors.layouts.app') 
@section('title' , 'Offer Managment') 
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Offer Managment
            {{--  <small>advanced tables</small>  --}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Offer Managment</a></li>
            {{--  <li class="active">Data tables</li>  --}}
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
                        <div class="box-header with-border">
                            <div class="pull-right">
                                <div class="btn-group pull-right" style="margin-right: 10px">
                                    <a href="{{ route('vendor.offer.create') }}" class="btn btn-sm btn-success" title="New">
                                             <i class="fa fa-save"></i><span class="hidden-xs">&nbsp;&nbsp;Add an Offer</span>
                                          </a>
                                </div>
                            </div>
                        </div>
                        @if(count($productOffers)
                        < 1) <p>no offers</p>
                            @else
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Value</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productOffers as $productOffer)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ optional($productOffer->product)->name_en}}</td>
                                        <td>{{ $productOffer->is_fixed ? $productOffer->fixed.' KD' :  $productOffer->percentage . '%' }}</td>
                                        <td>{{ $productOffer->from}}</td>
                                        <td>{{ $productOffer->to }}</td>
                                        <td>

                                                <a href="{{ route('vendor.offer.edit' ,[$productOffer->id])}}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            <a href="{{ route('vendor.offer.destroy' ,[$productOffer->id])}}">
                                                <i class="fa fa-remove"></i>
                                            </a>
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
