@extends('vendors.layouts.app') 
@section('title' , 'My Profile') 
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
          Users
            {{--  <small>it all starts here</small>  --}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            {{--  <li><a href="#"></a></li>  --}}
            <li class="active">User List</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="pull-right">
                            <div class="btn-group pull-right" style="margin-right: 10px">
                                <a href="{{ route('vendor.user.create') }}" class="btn btn-sm btn-success" title="New">
                                    <i class="fa fa-save"></i><span class="hidden-xs">&nbsp;&nbsp;Add User</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        @if(count($user->subvendors)
                        < 1) <p>no users</p>
                        @else
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name english</th>

                                    <th>Edit</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($user->subvendors as $vendor )
                                    <tr>
                                        <td>{{ $loop->index }}</td>
                                        <td>{{ $vendor->name}}</td>

                                        <td>
                                            {{--  <a href="{{ route('vendor.product.show' , [$product->id])}}">
                                            <i class="fa fa-eye"></i>  --}}
                                            <a href="{{ route('vendor.user.edit' , [$vendor->id])}}">
                                                <i class="fa fa-edit"></i>

                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif

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
