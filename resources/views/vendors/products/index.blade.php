@extends('vendors.layouts.app') 
@section('title' , 'Offer Managment') 
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Products Managment
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
                     <div class="box-header with-border">
                            <div class="pull-right">
                                <div class="btn-group" style="margin-right: 10px" data-toggle="buttons">
                                    <label class="btn btn-sm btn-dropbox filter-btn" title="Filter">
                                        <input type="checkbox"><i class="fa fa-filter"></i><span class="hidden-xs">&nbsp;&nbsp;Filter</span>
                                    </label>
                                </div>
                                </span>
                            </div>

                        </div>
                        <div class="box-header with-border {{ $request->has('search') ? '' : 'hide' }}" id="filter-box">
                            <form action="{{ route('vendor.product.index')}}" class="form-horizontal"
                                  pjax-container="" method="get">
                                <div class="box-body">
                                    <div class="row">


                                        <input type="hidden" name="search" value="1">

                                        <div class="fields-group">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label"> Produc</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-addon">
                                                           
                                                        </div>
                                                        <input 
                                                               class="form-control "
                                                               placeholder="product" name="product"
                                                               value="{{ $request->has('product') ? $request->product : '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                       



                                        <div class="box-footer">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-8">
                                                        <div class="btn-group pull-left">
                                                            <button class="btn btn-info submit btn-sm"><i
                                                                    class="fa fa-search"></i>&nbsp;&nbsp;Search
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> </div>
                                </div>
                            </form>
                        </div>
                    <div class="box-header">
                        <h3 class="box-title">Products</h3>
                    </div>
                    <div class="box-body">
                        <div class="box-header with-border">
                            <div class="pull-right">
                                <div class="btn-group pull-right" style="margin-right: 10px">
                                    <a href="{{ route('vendor.product.create') }}" class="btn btn-sm btn-success" title="New">
                                             <i class="fa fa-save"></i><span class="hidden-xs">&nbsp;&nbsp;Add a Product</span>
                                          </a>
                                </div>
                            </div>
                        </div>
                        @if(count($products)
                        < 1) <p>no products</p>
                            @else
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Type</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $product->name}}</td>
                                        @php
                                        $type = 'Normal';
                                        if($product->product_type == 1 )
                                        {
                                         $type = 'Boxed';
                                        }
                                        else if($product->product_type == 2 )
                                        {
                                         $type = 'Life';
                                        }
                                        else if($product->product_type == 3 )
                                        {
                                         $type = 'Felline';
                                        }
                                        else if($product->product_type == 4)
                                        {
                                         $type = 'Carton';
                                        }
                                        else if($product->product_type == 5)
                                        {
                                         $type = 'Sack';
                                        }
                                        @endphp
                                        <td>{{ $type  }}</td>

                                        <td>

                                                <a href="{{ route('vendor.product.edit' ,[$product->id])}}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            <a href="{{ route('vendor.product.delete' ,[$product->id])}}">
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
