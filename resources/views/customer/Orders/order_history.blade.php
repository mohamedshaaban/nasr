@extends('layout.app')
@section('content')


  <section class="my-profile">
    <div class="container">
       @include('customer.includes.profile_menu')
      <div class="col-md-9 ">

          <orders :orders_list="{{ json_encode($orders)}}"></orders>
      </div>
    </div>
  </section>
    <div style="clear:both;height: 15px;"></div>
    @include('includes.works');

@endsection
