
@extends('layout.app')
@section('content')

<div class="container innr-cont-area">
    <div class="row">


        @include('customer.includes.profile_menu')
        <div class="col-sm-9 right-sec addrss-book">
            @if(count($userAddress) > 0 )
            <h4>{{ __('website.address_book_label')}}</h4>
            <div class="row">
               @foreach($userAddress as $address)
                <div class="col-sm-6" id="address{{ $address->id }}">
                    <div class="box">
                        <div class="address_edithold">
                        @if($address->address_type == 1 && $address->is_default == 1 )

                        <h5>{{ __('website.default_billing_address_label')}} </h5>
                        @elseif($address->address_type == 2&& $address->is_default == 1 )

                            <h5>{{ __('website.default_shipping_address_label')}}</h5>
                            @endif
                        <span> <a class="edit editaddresshref" href="#addressForm" onclick="editAddress({{ $address->id }})">{{ __('website.edit_label')}}</a>
                        <a class="deleteaddresshref" href="#addressForm" onclick="deleteAddress({{ $address->id }})">{{ __('website.delete_label')}}</a>
                        </span>
                           <div style="clear: both"></div>
                        </div>

                        <span>
                  {{ $address->first_name }} {{ $address->second_name }}<br>
                  {{ $address->phone_no }}<br>
                  {{ $address->countries->name }}<br>
                  {{ $address->city }}<br>
                  {{ $address->fax }}<br>
                  {{ $address->company }}<br>
                  {{ $address->zip_code }}
                </span>
                    </div>
                </div><!--/.col-sm-6-->
               @endforeach
            </div><!--/.row-->
            @endif
            <br>
            <h4>{{ __('website.add_new_address_label')}}</h4>
            <div class="add-newaddrss">
                <form class="row" action="{{ route('customer.submit_address') }}" id="addressForm" method="post">
                    @csrf

                    <input type="hidden" name="address_id" value="">
                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="{{ __('website.first_name_label')}}" name="first_name" value="{{ $editingAddress['first_name'] ? $editingAddress['first_name'] : '' }}" required>
                    </div><!--/.col-sm-6-->
                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="{{ __('website.last_name_label')}}" name="second_name"  value="{{ $editingAddress['second_name'] ? $editingAddress['second_name'] : '' }}" required>
                    </div><!--/.col-sm-6-->
                    <div class="col-sm-6">
                        <select class="form-control" name="country_id" id="country_id" required onchange="getgovernate(this.value)">
                            <option value="">{{ __('website.please_select_label')}}</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @if($country->id ==$editingAddress['country_id'] )  selected @endif> {{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div><!--/.col-sm-6-->
                    <div class="col-sm-6">
                        <select class="form-control" name="governate_id" id="governate_id" required onchange="getarea(this.value)">
                            <option value="">{{ __('website.please_select_label')}}</option>
                            @foreach($governorates as $governorate)
                                @if($governorate)<option value="{{ $governorate->id }}" @if($governorate->id ==$editingAddress['governate_id'] )  selected @endif> {{ $governorate->name }}</option>@endif
                            @endforeach
                        </select>
                    </div><!--/.col-sm-6-->
                    <div class="col-sm-6">
                        <select class="form-control" name="area_id" id="area_id" required>
                            <option value="">{{ __('website.please_select_label')}}</option>
                            @foreach($areas as $area)
                                @if($area)<option value="{{ $area->id }}" @if($area->id ==$editingAddress['area_id'] )  selected @endif> {{ $area->name }}</option>@endif
                            @endforeach
                        </select>
                    </div><!--/.col-sm-6-->

                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="{{ __('website.city_label')}}*" name="city" value="{{ $editingAddress['city'] ? $editingAddress['city'] : '' }}"  required>
                    </div><!--/.col-sm-6-->

                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="{{ __('website.blook_label')}} *" name="block" value="{{ $editingAddress['block'] ? $editingAddress['block'] : '' }}"  required>
                    </div><!--/.col-sm-6-->


                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="{{ __('website.street_label')}} *" name="street" value="{{ $editingAddress['street'] ? $editingAddress['street'] : '' }}"  required>
                    </div><!--/.col-sm-6-->

                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="{{ __('website.avenue_label')}}" name="avenue" value="{{ $editingAddress['avenue'] ? $editingAddress['avenue'] : '' }}"  >
                    </div><!--/.col-sm-6-->
                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="{{ __('website.fax_label')}} *" name="fax" value="{{ $editingAddress['fax'] ? $editingAddress['fax'] : '' }}"  required>
                    </div><!--/.col-sm-6-->
                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="{{ __('website.floor_label')}}" name="floor" value="{{ $editingAddress['floor'] ? $editingAddress['floor'] : '' }}"  >
                    </div><!--/.col-sm-6-->
                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="{{ __('website.flat_label')}}" name="flat" value="{{ $editingAddress['flat'] ? $editingAddress['flat'] : '' }}"  >
                    </div><!--/.col-sm-6-->



                    <div class="col-sm-6">
                        <input type="number" class="form-control phone-appearance" placeholder="{{ __('website.phone_label')}} *" name="phone_no" value="{{ $editingAddress['phone_no'] ?$editingAddress['phone_no']  : Auth::user()->mobile}}"  required>
                    </div><!--/.col-sm-6-->


                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="{{ __('website.company_label')}} " name="company" value="{{ $editingAddress['company'] ? $editingAddress['company'] : '' }}"  >
                    </div><!--/.col-sm-6-->
                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="{{ __('website.zip_label')}} " name="zip_code" value="{{ $editingAddress['zip_code'] ? $editingAddress['zip_code'] : '' }}"  >
                    </div><!--/.col-sm-6-->

                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="{{ __('website.extra_direction_label')}}" name="extra_direction" value="{{ $editingAddress['extra_direction'] ? $editingAddress['extra_direction'] : '' }}"  >
                    </div><!--/.col-sm-6-->
                    <div class="col-sm-6">
                        <label class="checkbox-sml mt-20">{{ __('website.use_default_billing_label')}}
                            <input type="checkbox" id="billing_default" name="is_default" value="1"  @if($editingAddress['address_type'] ==1 && $editingAddress['is_default'] == 1)) checked @endif>
                            <span class="checkmark"></span>
                        </label>
                    </div><!--/.col-sm-6-->
                    <div class="col-sm-6">
                        <label class="checkbox-sml mt-20">{{ __('website.use_default_shipping_label')}}
                            <input type="checkbox" id="shipping_default" name="is_default" value="2"  @if($editingAddress['address_type'] ==2 && $editingAddress['is_default'] == 1)) checked @endif>
                            <span class="checkmark"></span>
                        </label>
                    </div><!--/.col-sm-6-->
                    <div class="col-sm-12 mt-30">
                        <button class="btn-lg btn-success rounded-0" type="submit">{{ __('website.save_Address_label')}}</button>
                    </div><!--/.col-sm-12-->
                </form>
            </div><!--/.row-->
        </div><!--/.col-sm-9-->
    </div>

</div><!--/.innr-cont-area-->
@include('includes.works');

@endsection
