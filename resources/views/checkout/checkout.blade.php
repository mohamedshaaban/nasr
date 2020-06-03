@extends('layout.app')
@section('content')

    @php
        $totalcrt = 0 ;
        $totalcount = 0 ;
        $total  = 0 ;
        $itemsQuantity=array();
        foreach($cartItems as $item)
        {
            $totalcount++ ;
            $itemsQuantity[$item->product_id]=$item->quantity;
          $totalcrt += $item->total;
        }
        $totalAfterGroup= 0 ;

    @endphp
<div class="container">
    <ul class=" breadcrumb">
        <li><a href="{{ route('home') }}">{{__('website.home_label')}}</a></li>
        <li class="active">{{__('website.delivery_address_label')}}</li>
    </ul>
</div>

<div class="container innr-cont-area">
    <div class="row">
        <div class="col-sm-12 mt-30">
            <h3 class="innerpage-head">{{__('website.delivery_address_label')}}</h3>
        </div>
        <div class="col-sm-7 checkout">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#Step1" aria-controls="home" role="tab" data-toggle="tab">
                        Step1
                    </a></li>
                <li role="presentation"><a href="#Step2" aria-controls="profile" role="tab" data-toggle="tab">
                        Step2
                    </a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="Step1">
                    <h4>Shipping Address</h4>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="box">
                                <label class="radio-btn select-box">
                                    <input type="radio" id="radio" name="radio" >
                                    <span class="checkmark"></span>
                                </label>
                                <span class="data">
                      Gagan Girgiri<br>
                      newadd<br>
                      Kuwait, Al Asimah 30000<br>
                      Kuwait<br>
                      69995452<br>
                    </span>
                                <br>
                                <div class="text-center"><button class="btn btn-success">Ship Here</button></div>
                            </div>
                        </div><!--/.col-sm-6-->
                        <div class="col-sm-5 col-sm-offset-1">
                            <div class="box">
                                <label class="radio-btn select-box">
                                    <input type="radio" id="radio" name="radio">
                                    <span class="checkmark"></span>
                                </label>
                                <span class="data">
                      Gagan Girgiri<br>
                      newadd<br>
                      Kuwait, Al Asimah 30000<br>
                      Kuwait<br>
                      69995452<br>
                    </span>
                                <br>
                                <div class="text-center"><button class="btn btn-success">Ship Here</button></div>
                            </div>
                        </div><!--/.col-sm-6-->
                        <div class="col-sm-12">
                            <button class="btn-lg btn-success mt-30">Add New Address</button>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="Step2">

                    <h4>{{__('website.delivery_address_label')}}</h4>
                    <div class="row">
                        <div class="col-sm-6">
                    <span class="data">
                      Amirah Salah<br>
                      Salmiya<br>
                      Street 2, Street<br>
                      City 242564<br>
                      Building 6<br>
                      Floor 4<br>
                      Flat No 38<br>
                      Kuwait<br>
                      +956124875
                    </span>
                        </div>
                    </div>

                    <h4 class="mt-30">Choose Payment Method</h4>
                    <div class="row payment-mthd">
                        <div class="col-xs-6 col-sm-6 col-md-3 mt-20">
                            <label class="radio-btn"><img src="images/knet.png">
                                <input type="radio" id="radio" name="radio" >
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-3 mt-20">
                            <label class="radio-btn"><img src="images/cod.png">
                                <input type="radio" id="radio" name="radio" >
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <br><br>
                    <button class="btn-lg btn-success">Place Order</button>
                    <hr>
                    <div class="disc-code">
                        <a class="" data-toggle="collapse" data-parent="#stacked-menu" href="#disc-code" aria-expanded="true">Apply Discount Code</a>
                        <div class="collapse clearfix mt-15" id="disc-code" aria-expanded="true" style="">
                            <input type="" class="form-control" style="max-width: 300px;" placeholder="Enter Code" name="">
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/.col-sm-8-->

        <div class="col-sm-5">
            <div class="side-summary">
                <h3>Ordar SUMMARY</h3>
                <div class="data clearfix">
                    <div class="row" style="display: none">
                        <div class="col-xs-6 list">
                            Subtottal
                        </div>
                        <div class="col-xs-6 list text-right">
                            <span >{{ $totalcrt }}</span> {{__('website.kd_label')}}
                        </div>
                    </div>
                    <div class="row" style="display: none">
                        <div class="col-xs-6 list">
                            Total Delivery Charge
                        </div>
                        <div class="col-xs-6 list text-right">
                            <span id="delivery_charges" >00</span> {{__('website.kd_label')}}
                        </div>
                    </div>
                    <div class="row"  style="display: none">
                        <div class="col-xs-12">
                            <div class="total">
                                <div class="row">
                                    <div class="col-xs-6">
                                        Order Total
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <span id="total_delivery_charges">{{ $totalcrt  }}</span> {{__('website.kd_label')}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="heading" data-toggle="collapse" data-parent="#stacked-menu" href="#summary" aria-expanded="true">{{ $ca }}  {{__('website.items_in_cart')}}</a>
                <ul class="collapse in listing" id="summary" aria-expanded="true" style="">
                    @foreach($cartItems as $item)
                        <li class="row">
                            <div class="col-sm-4 img">
                                <img src="{{ asset( $item->image) }}">
                            </div>
                            <div class="col-sm-4 pl-0 data">
                                <strong>{{ $item->product->name }}</strong><br>
                                <span class="text-drkgreen">{{ $item->product->vendor->code }}</span>
                                <small>  {{ $item->product->code }}</small><br>
                                {{__('website.qty_label')}}: {{ $item->quantity }} <br>
                            </div>
                            <div class="col-sm-4 price">
                            </div>
                        </li><!--/li-->
                    @endforeach
                </ul><!--/nav-->
            </div>
        </div>

    </div>

</div><!--/.innr-cont-area-->

    @include('includes.works');


@endsection
