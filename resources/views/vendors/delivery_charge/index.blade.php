@extends('vendors.layouts.app') 
@section('title' , 'Delivery Charge') 
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Delivery Charge {{-- <small>it all starts here</small> --}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            {{--
            <li><a href="#">  </a></li> --}}
            <li class="active">Delivery Charge</li>
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
        <div class="container">
            @if((count($errors) > 0)) @foreach ($errors->all() as $error)

            <div class="pad margin no-print">
                <div class="callout callout-warning" style="margin-bottom: 0!important;">
                    <h4><i class="fa fa-warning"></i> Note:</h4>
                    {{ $error }}
                </div>
            </div>
            @endforeach @endif
            <div class="col-md-11">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#delivery" data-toggle="tab">Delivery Charge</a></li>
                         <li><a href="#analytics" data-toggle="tab">Analytics</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="delivery">
                            <form action="{{ route('vendor.delivery_charge.store')}}" method="post">
                                @csrf @foreach($governorates as $governorate ) @if(count( $governorate->areas)) @if($loop->iteration % 2 == 0)
                                <div class="row">
                                    @endif
                                    <div class="col-md-6 ">
                                        <div class="box box-default collapsed-box">
                                            <div class="box-header with-border">
                                                <h3 class="box-title"> {{ $governorate->name}}</h3>

                                                <div class="box-tools pull-right">
                                                    @if(isset($vendorGovernorates[$governorate->id]))
                                                    <span data-toggle="tooltip" title="available areas" class="badge bg-red" data-original-title="available areas">{{ count($vendorGovernorates[$governorate->id])}}</span>                                                    @endif
                                                    <span data-toggle="tooltip" title="# of areas" class="badge bg-light-blue" data-original-title="# of areas">{{ count($governorate->areas)}}</span>
                                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                                </div>

                                            </div>
                                            <div class="box-body">
                                                <div class="box box-info">
                                                    <div class="box box-solid ">
                                                        <div class="box-body delivery_area_checkboxs_{{ $governorate->id}}">
                                                            <div class="col-xs-12">
                                                                <div class="col-md-6">Area Name</div>
                                                                <div class="col-md-3">Delivery Charge</div>

                                                            </div>
                                                            @foreach($governorate->areas as $area)
                                                            <div class="col-xs-12">
                                                                <span class="col-md-6">
                                                               <label>{{ $area->name}}</label>
                                                               @if(in_array($area->id ,array_keys($vendorAreas)))
                                                               <input checked  value="{{ $area->id }}" governorate_id="{{ $governorate->id}}" type="checkbox" class="minimal-red delivery_areas delivery_area_checkbox_{{$area->id}}"> 
                                                               @else
                                                               <input   value="{{ $area->id }}" governorate_id="{{ $governorate->id}}" type="checkbox" class="minimal-red delivery_areas delivery_area_checkbox_{{$area->id}}">                                                                
                                                               @endif
                                                            </span>
                                                                <span class="col-md-3">
                                                                @if(in_array($area->id ,array_keys($vendorAreas)))
                                                                <input          name="delivery_areas[{{$area->id}}][delivery_charge]" pattern="\d{1,5}" title="only positive number"  value="{{ $vendorAreas[$area->id]['delivery_charge']}}" placeholder="0" type="text" class="form-control delivery_area_input_{{ $area->id }}">
                                                                @else 
                                                                <input disabled name="delivery_areas[{{$area->id}}][delivery_charge]" pattern="\d{1,5}" title="only positive number"  type="text" class="form-control delivery_area_input_{{ $area->id }}">
                                                                @endif
                                                            </span>

                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($loop->iteration % 2 == 0)
                                </div>
                                @endif @endif @endforeach

                                <input type="submit" class="btn btn-block btn-primary btn-flat">
                            </form>


                        </div>
                        <div class="tab-pane" id="minimum">

                        </div>
                        <div class="tab-pane" id="analytics">
                            <div class="box-body">

                                <div class="fields-group">
                                    <div class="col-sm-2">
                                        <label for="logo">Month</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <select class="form-control select2" id="analytic_from_month" name="analytic_from_month" style="width: 100%;">
                                                <option selected disabled>Select Month</option>
                                            @for($month=1; $month <= 12; $month++): ?>
                                            <option value="{{ $month }}">{{ $month }}</option>
                                        @endfor
                                    </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="logo 2">Year</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <select class="form-control select2" id="analytic_from_year" name="analytic_from_year" style="width: 100%;">
                                                <option selected disabled>Select Year</option>
                                            @for($year=2018; $year <= \Carbon\Carbon::today()->format('Y'); $year++): ?>
                                            <option  value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="logo">Month</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <select class="form-control select2" id="analytic_to_month" name="analytic_to_month" style="width: 100%;">
                                                    <option selected disabled>Select Month</option>
                                                @for($month=1; $month <= 12; $month++): ?>
                                                <option value="{{ $month }}">{{ $month }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="logo 2">Year</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <select class="form-control select2" id="analytic_to_year" name="analytic_to_year" style="width: 100%;">
                                                    <option selected disabled>Select Year</option>
                                                @for($year=2018; $year <= \Carbon\Carbon::today()->format('Y'); $year++): ?>
                                                <option  value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="logo 2">Filter</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <button id="btn_analytic" class="btn btn-block btn-primary btn-flat">Filter</button>
                                    </div>
                                </div>
                                
                                
                            </div>
                            <div class="box-body analytics_table">
                            </div>

                           
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </section>


</div>
@endsection
 
@section('lower_javascript')
// analyices script
<script>
    $(document).ready(function(){
       $('#btn_analytic').click(function(){
           from_month = $('#analytic_from_month').val();
           from_year = $('#analytic_from_year').val();
           to_month = $('#analytic_to_month').val();
           to_year = $('#analytic_to_year').val();
          
           
           url = '{{ route('analytics.get_ajax')}}'

           $.ajax({
            type : 'GET',
            url: url + '?from_month=' + from_month + '&from_year=' + from_year + '&to_month=' + to_month + '&to_year=' +to_year  ,
            success : function(data){
                $('.analytics_table').html('');
                $('.analytics_table').append(data.html);
            }
        })  
       });
       
    });

</script>

// delivery charges script
<script>
    $(document).ready(function(){
          $('.delivery_areas').on('ifChanged', function(event) {
            area_id = $(this).val();
            if(event.target.checked){
                // delivery charge
                $(".delivery_area_input_" + area_id).prop('disabled' , false);
                $('.delivery_area_input_' + area_id).val(0);
                // minimun order
                $(".delivery_area_input_min_" + area_id).prop('disabled' , false);
                $('.delivery_area_input_min_' + area_id).val(1);
            }else{
                // delivery charge
                $('.delivery_area_input_' + area_id).val();
                $(".delivery_area_input_" + area_id).prop('disabled' , true);
                // minimun order
                $('.delivery_area_input_min_' + area_id).val();
                $(".delivery_area_input_min_" + area_id).prop('disabled' , true);
            }
        });
    });

</script>
@endsection
