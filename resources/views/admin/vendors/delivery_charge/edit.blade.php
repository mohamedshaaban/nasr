@extends('admin::index') 
@section('content')

<section class="content-header">
    <h1>
        <span> vendors chat </span>
    </h1>
</section>

<section class="content">
    @include('admin::partials.alerts')
    @include('admin::partials.exception')
    @include('admin::partials.toastr')

    <div class="box">
        <div class="box-body table-responsive no-padding">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#delivery" data-toggle="tab">Delivery Charge</a></li>

                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="delivery">
                            <form action="{{ route('admin.delivery_charger.update' , [$vendor_id])}}" method="post">
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
                                                               <input checked  value="{{ $area->id }}" governorate_id="{{ $governorate->id}}" type="checkbox" class="icheckbox_minimal-blue delivery_areas delivery_area_checkbox_{{$area->id}}"> 
                                                               @else
                                                               <input   value="{{ $area->id }}" governorate_id="{{ $governorate->id}}" type="checkbox" class="icheckbox_minimal-blue delivery_areas delivery_area_checkbox_{{$area->id}}">                                                                
                                                               @endif
                                                            </span>
                                                                <span class="col-md-3">
                                                                @if(in_array($area->id ,array_keys($vendorAreas)))
                                                                <input          name="delivery_areas[{{$area->id}}][delivery_charge]"   value="{{ $vendorAreas[$area->id]['delivery_charge']}}"  type="text" class="form-control delivery_area_input_{{ $area->id }}">
                                                                @else 
                                                                <input disabled name="delivery_areas[{{$area->id}}][delivery_charge]"  type="text" class="form-control delivery_area_input_{{ $area->id }}">
                                                                @endif
                                                            </span>

                                                            </div>
                                                            @endforeach
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

                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer clearfix">
        </div>
    </div>


</section>

<script>
    $(document).ready(function(){
  
          $('.delivery_areas').on('change', function(event) {
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
