<div class="col-sm-3 mt-30">
    <div class="side-navigation">
<ul role="tablist">
    <li role="presentation" class="{{ (\Request::route()->getName() == 'customer.dashboard') ? 'active' : '' }}"><a href="{{ route('customer.dashboard') }}" >{{ __('website.menu_dashboard_label')}}</a></li>
    <li role="presentation" class="{{ (\Request::route()->getName() == 'customer.address-book') ? 'active' : '' }}"><a href="{{ route('customer.address-book') }}" >{{ __('website.menu_address_book_label')}}</a></li>
    <li role="presentation" class="{{ (\Request::route()->getName() == 'customer.my-orders') ? 'active' : '' }}"><a href="{{ route('customer.my-orders') }}" >{{ __('website.my_orders_label')}}</a></li>
    <li role="presentation" class="{{ (\Request::route()->getName() == 'customer.track-orders') ? 'active' : '' }}"><a href="{{ route('customer.track-orders') }}" >{{ __('website.track_orders_label')}}</a></li>
    <li role="presentation"><a href="{{ route('customer.logout') }}" >{{ __('website.logout')}}</a></li>
</ul>
    </div>
</div>
