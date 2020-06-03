
@extends('layout.app')
@section('content')

<div class="container innr-cont-area">
    <div class="row">


        @include('customer.includes.profile_menu')
        <div class="col-sm-9 my-account my-order mt-30">
            <div class="heading">{{ __('website.my_dash_label')}}</div>
            <div class="table-cvr">
                <div class="recent-rder">
                    {{ __('website.recent_order_label')}}
                    <a class="pull-right" href="{{ route('customer.my-orders') }}">{{ __('website.View All')}}</a>
                </div>

                <recentorders :orders="{{ json_encode($orders) }}"></recentorders>
            </div>

            <br>
            <h4>{{ __('website.menu_account_info_label')}}</h4>
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <h5>{{ __('website.Contact_Information_label')}}</h5>
                        {{ $user->name }}<br>
                        {{ $user->email }}<br>
                        <a class="edit" href="{{ route('customer.account-info') }}">{{ __('website.edit_label')}}</a> | <a class="edit" href="{{ route('customer.account-info') }}">{{ __('website.Change_password_label')}}</a>
                        </ul>
                    </div>
                </div><!--/.col-sm-6-->

            </div><!--/.row-->
            <br>
            <h4>{{ __('website.menu_account_info_label')}}</h4>
            <div class="row .my-account ">
                @foreach($userAddress as $address )

                    <div class="col-sm-6">
                        <div class="box">

                            @if($address->address_type == 1 && $address->is_default == 1 )
                                <h5>{{ __('website.default_billing_address_label')}} </h5>
                            @elseif($address->address_type == 2 && $address->is_default == 1 )
                                <h5>{{ __('website.default_shipping_address_label')}}</h5>
                            @endif

                            <span>
                  {{ $address->first_name }} {{ $address->second_name }}<br>
                  {{ $address->phone_no }}<br>
                  {{ $address->fax }}<br>
                  {{ $address->company }}<br>
                  {{ $address->zip_code }}<br>
                  {{ $address->city }}<br>
                  {{ $address->countries->name }}
                </span>
                        </div>
                        <a class="edit" href="{{ route('customer.editaddress-book', $address->id) }}#addressForm" >{{ __('website.edit_label')}}</a>
                    </div><!--/.col-sm-6-->
              @endforeach
            </div><!--/.row-->

        </div><!--/.col-sm-9-->
    </div>

</div><!--/.innr-cont-area-->
@include('includes.works');
@endsection
<script>
    import RecentOrders from "../../js/components/orders/RecentOrders";
    export default {
        components: {RecentOrders}
    }
</script>
