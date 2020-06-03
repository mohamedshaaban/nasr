@extends('layout.app')
@section('content')


    <div class="container innr-cont-area">
        <div class="row">

                <div class="col-md-9 trackmy-order mt-20">
                    <div class="order-id">{{ __('website.Order Id')}}: #{{ $order->unique_id }}</div>
                    <h4>{{ __('website.Customername_label')}}: {{ Auth::user()->name }}</h4>
                    <span class="date">{{ __('website.date_label')}}: {{ $order->order_date }}</span>
                    @php
                        $vendorOrderItems = \App\Models\OrderProduct::where('order_id' , $order->id)->groupBy('vendor_id')->pluck('vendor_id');
                            $i = 0 ;
                    @endphp
                    @foreach($vendorOrderItems as $key )
                        @php
                            $vendor  = \App\Models\Vendors::find($key);

                        @endphp
                        @if($vendor)
                            <div class="table-head">

                                <small>{{ __('website.code_label')}}: {{ $vendor->code }}</small>
                            </div>
                        @endif
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('website.order_Status_label')}}</th>
                                <th scope="col">{{ __('website.order_updated_label')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $ordertrack = \App\Models\OrderTrack::where('order_id', $order->id)->where('vendor_id' , $key)->with('orderstatus')->get();
                                $orderproducts = \App\Models\OrderProduct::where('order_id', $order->id)->where('vendor_id' , $key)->with('product')->get();

                            @endphp

                            @foreach($ordertrack as $track)
                                <tr>
                                    <td>{{ $track->orderstatus->title }}</td>
                                    <td>{{ $track->created_at }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="2">
                                    <div class="show-items clearfix">
                                        <a class="heading" data-toggle="collapse" data-parent="#stacked-menu" href="#items{{ $i }}" aria-expanded="true">{{ __('website.show_items_label')}}</a>
                                        <div class="items{{ $i }} collapse in" id="items{{ $order->id }}" aria-expanded="true" style="">
                                            <strong>{{ __('website.item_label')}}</strong>
                                            @foreach($orderproducts as $item)
                                                <tr>
                                                    <td>
                                                <p>{{ $item->product->name }}</p>
                                                    </td>
                                                    <td>
                                                <img
                                                src="{{ $item->product->main_image_path }}"
                                                style="max-height: 150px;"/>
                                                    </td>
                                                    <td>
                                                <small>{{ $item->extraoption }}<br> {{ __('website.qty_label')}}: {{ $item->quantity }}</small>
                                                        <select onchange="returnitem({{ $item->id }} , this.value)">
                                                            @for($i- 0 ; $i < $item->quantity ; $i++)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                @endfor
                                                        </select>
                                                    </td>

                                                </tr>
                                                <br>
                                                <br>
                                            @endforeach
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <?php
                        $i++;
                        ?>
                    @endforeach

                </div><!--/.col-sm-9-->

        </div>
    </div><!--/.innr-cont-area-->
<script>

    function returnitem(itemId,quantity) {
        $.ajax({
            method: "get",
            url: "/returnOrderItem/"+itemId+'/'+quantity,
            success: function(result) {
                if(result.status == true)
                {
                    // $('#address'+id).remove();
                    swal("Your Item Has Been Returned");
                }
            }
        });
    }
</script>
    @include('includes.works');

@endsection
