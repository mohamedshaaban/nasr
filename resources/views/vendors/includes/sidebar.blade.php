<aside class="main-sidebar">

    <section class="sidebar">
        <div class="user-panel" style="height: 32px;">
            <div class="pull-left image">
             </div>
            <div class="pull-left info" style="height: 41px;">
                <p> {{ \Auth::guard('vendor')->user()->name }}</p>
                {{--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>--}}
            </div>
        </div>


        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">HEADER</li>
            <!-- Optionally, you can add icons to the links -->
            <li class=""><a href="{{ route('vendor.dashboard.index')}}"><i class="fa fa-dashboard"></i>
                    <span>Dashboard</span></a></li>
            {{--<li><a href="{{ route('vendor.tags.index')}}"><i class="fa fa-tag"></i> <span>Tags</span></a></li>--}}
            @if( \Auth::guard('vendor')->user()->permission == \App\Models\Vendors::VENDOR_FULL_ACCESS)

                <li><a href="{{ route('vendor.profile.index')}}"><i class="fa fa-tag"></i>
                        <span>Vendor Information</span></a></li>
                <li><a href="{{ route('vendor.user.index')}}"><i class="fa fa-tag"></i> <span>Users</span></a></li>
            @endif
            @if( \Auth::guard('vendor')->user()->permission == \App\Models\Vendors::VENDOR_FULL_ACCESS ||  \Auth::guard('vendor')->user()->permission == \App\Models\Vendors::VENDOR_SALES_ACCESS)
                <li><a href="{{ route('vendor.order.index')}}"><i class="fa fa-tag"></i> <span>Orders</span></a></li>

            @endif
            @if( \Auth::guard('vendor')->user()->permission == \App\Models\Vendors::VENDOR_FULL_ACCESS )

                <li><a href="{{ route('vendor.payment.index')}}"><i class="fa fa-money"></i> <span>Payment</span></a>
                </li>
                {{-- <li><a href="{{ route('vendor.commsiision.index')}}"><i class="fa fa-money"></i> <span>Commission</span></a></li> --}}
            @endif
            @if( \Auth::guard('vendor')->user()->permission == \App\Models\Vendors::VENDOR_FULL_ACCESS ||  \Auth::guard('vendor')->user()->permission == \App\Models\Vendors::VENDOR_SALES_ACCESS)
                <li class="treeview">
                    <a href="#"><i class="fa fa-link"></i> <span>Reports</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">

                        <li><a href="{{ route('vendor.reports' ,['sales'])}}"><i class="fa fa-circle-o text-yellow"></i>Sales</a>
                        </li>
                        <li><a href="{{ route('vendor.reports' ,['inventory'])}}"><i
                                    class="fa fa-circle-o text-yellow"></i>Inventory</a></li>
                        <li><a href="{{ route('vendor.reports' ,['delivery'])}}"><i
                                    class="fa fa-circle-o text-yellow"></i>Delivery</a></li>
                        <li><a href="{{ route('vendor.reports' ,['orders'])}}"><i
                                    class="fa fa-circle-o text-yellow"></i>Orders</a></li>
                        <li><a href="{{ route('vendor.reports' ,['commission'])}}"><i
                                    class="fa fa-circle-o text-yellow"></i>Commission</a></li>

                    </ul>
                </li>
            @endif
            @if( \Auth::guard('vendor')->user()->permission == \App\Models\Vendors::VENDOR_FULL_ACCESS ||  \Auth::guard('vendor')->user()->permission == \App\Models\Vendors::VENDOR_PRODUCT_ACCESS)

                <li class="treeview">
                    <a href="#"><i class="fa fa-link"></i> <span>Products</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('vendor.product.index')}}"><i class="fa fa-circle-o text-yellow"></i>products</a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-link"></i> <span>Offer Managment</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('vendor.offer.index')}}"><i class="fa fa-circle-o text-yellow"></i>Offer
                                Managment</a></li>
                    </ul>
                </li>
            @endif

        </ul>

    </section>
</aside>
