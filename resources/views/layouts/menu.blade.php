<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
       <!-- Search form -->
        <form role="search" class="navbar-form">
            <div class="form-group">
                <input type="text" placeholder="Search" class="form-control">
                <button type="submit" class="btn search-button"><i class="fa fa-search"></i></button>
            </div>
        </form>
        <div class="clearfix"></div>
        <!--- Profile -->
        <div class="profile-info">
            <div class="col-xs-4">
              <a href="profile.html" class="rounded-image profile-image"><img src="{{ URL::asset('public/assets/images/users/icons8-user-80.png')}}"></a>
            </div>
            <div class="col-xs-8">
                <div class="profile-text">Welcome <b>{{ Auth::user()->name }}</b></div>
                <div class="profile-buttons">
                  <a href="javascript:;"><i class="fa fa-envelope-o pulse"></i></a>
                  <a href="#connect" class="open-right"><i class="fa fa-comments"></i></a>
                  <a href="javascript:;" title="Sign Out"><i class="fa fa-power-off text-red-1"></i></a>
                </div>
            </div>
        </div>
        <!--- Divider -->
        <div class="clearfix"></div>
        <hr class="divider" />
        <div class="clearfix"></div>
        <!--- Divider -->
        <div id="sidebar-menu">
          <ul>
            @if(access_level_user('view','dashboard')=='allow')
            <li>
              <a href='{{ url('/') }}' class="@yield('class_dashboard')">
              <i class='icon-home-3'></i><span>Dashboard</span></a>
            </li>
            @endif
            <div id="chat_groups" class="widget transparent nomargin">
                <h2>Transaction</h2>
            </div>
            @if(access_level_user('view','pos')=='allow')
            <li>
              <a href='{{URL::to('pos')}}' class="@yield('class_pos')">
                <i class='glyphicon glyphicon-shopping-cart'></i>
              <span>POS</span>
            </a>
            </li>
            @endif
            <div id="chat_groups" class="widget transparent nomargin">
                <h2>Master Data</h2>
            </div>
@if(access_level_user('view','master_data')=='allow')
<li>
  <a href='javascript:void(0);'  class="@yield('class_master')">
    <i class='icon-feather'></i><span>Material Group</span>
    <span class="pull-right">
      <i class="fa fa-angle-down"></i>
    </span>
  </a>
  <ul style="@yield('class_ul')">
      @if(access_level_user('view','product_category')=='allow')
      <li><a href='{{URL::to('category')}}' class="@yield('m_category')"><span>Product Category</span></a></li>
      @endif
      @if(access_level_user('view','uom')=='allow')
      <li><a href='{{URL::to('uom')}}' class="@yield('m_uom')"><span>Unit of Measurement</span></a></li>
      @endif
      @if(access_level_user('view','currency')=='allow')
      <li><a href='{{URL::to('currency')}}' class="@yield('m_currency')"><span>Currency</span></a></li>
      @endif
      @if(access_level_user('view','warehouse')=='allow')
      <li><a href='{{URL::to('warehouse')}}' class="@yield('m_warehouse')"><span>Warehouse</span></a></li>
      @endif
      @if(access_level_user('view','warehouse_location')=='allow')
      <li><a href='{{URL::to('whLoc')}}' class="@yield('m_wh_location')"><span>Warehouse Location</span></a></li>
      @endif
      @if(access_level_user('view','payment')=='allow')
      <li><a href='{{URL::to('payment')}}' class="@yield('m_payment')"><span>Payment Method</span></a></li>
      @endif
      @if(access_level_user('view','percentage')=='allow')
      <li><a href='{{URL::to('percentage')}}' class="@yield('m_percentage')"><span>Percentage</span></a></li>
      @endif
      @if(access_level_user('view','tax')=='allow')
      <li><a href='{{URL::to('tax')}}' class="@yield('m_tax')"><span>Tax</span></a></li>
      @endif
      @if(access_level_user('view','product')=='allow')
      <li><a href='{{URL::to('product')}}' class="@yield('m_product')"><span>Product</span></a></li>
      @endif
  </ul>
</li>
@endif
@if(access_level_user('view','organization_group')=='allow')
<li><a href='javascript:void(0);'  class="@yield('class_organization')"><i class='icon-group'></i>
  <span>Organization Group</span>
  <span class="pull-right"><i class="fa fa-angle-down"></i></span></a>
    <ul style="@yield('class_ul_organization')">
      @if(access_level_user('view','division')=='allow')
      <li><a href='{{URL::to('division')}}' class="@yield('m_division')">
        <span>Division</span></a>
      </li>
      @endif
      @if(access_level_user('view','section')=='allow')
      <li><a href='{{URL::to('section')}}' class="@yield('m_section')">
        <span>Section</span></a>
      </li>
      @endif
      @if(access_level_user('view','counter')=='allow')
      <li><a href='{{URL::to('counter')}}' class="@yield('m_counter')">
        <span>Counter</span></a>
      </li>
      @endif
  </ul>
</li>
@endif
@if(access_level_user('view','customer_group')=='allow')
<li><a href='javascript:void(0);'  class="@yield('class_customer')"><i class='icon-user'></i>
  <span>Customer Group</span>
  <span class="pull-right"><i class="fa fa-angle-down"></i></span></a>
    <ul style="@yield('class_ul_customer')">
      @if(access_level_user('view','customer_class')=='allow')
      <li><a href='{{URL::to('custClass')}}' class="@yield('m_customer_class')"><span>Customer Class</span></a></li>
      @endif
      @if(access_level_user('view','customer')=='allow')
      <li><a href='{{URL::to('customer')}}' class="@yield('m_customer')"><span>Customer</span></a></li>
      @endif
  </ul>
</li>
@endif
@if(access_level_user('view','vendor_group')=='allow')
<li><a href='javascript:void(0);'  class="@yield('class_vendor')"><i class='fa fa-briefcase'></i>
  <span>Vendor Group</span>
  <span class="pull-right"><i class="fa fa-angle-down"></i></span></a>
    <ul style="@yield('class_ul_vendor')">
      @if(access_level_user('view','vendor_class')=='allow')
      <li><a href='{{URL::to('vendorClass')}}' class="@yield('m_vendor_class')"><span>Vendor Class</span></a></li>
      @endif
      @if(access_level_user('view','vendor')=='allow')
      <li><a href='{{URL::to('vendorList')}}' class="@yield('m_vendor')"><span>Vendor</span></a></li>
      @endif
  </ul>
</li>
@endif
<div id="chat_groups" class="widget transparent nomargin">
    <h2>System Management</h2>
</div>
@if(access_level_user('view','role')=='allow')
<li><a href='javascript:void(0);'  class="@yield('class_role')"><i class='fa fa-key'></i><span>Role Access</span>
  <span class="pull-right"><i class="fa fa-angle-down"></i></span></a>
    <ul style="@yield('class_ul_role')">
      @if(access_level_user('view','user')=='allow')
      <li><a href='{{URL::to('user')}}' class="@yield('m_user')"><span>User Management</span></a></li>
      @endif
      @if(access_level_user('view','priveleges')=='allow')
      <li><a href='{{URL::to('priveleges')}}' class="@yield('m_priveleges')"><span>Priveleges</span></a></li>
      @endif
      @if(access_level_user('view','module')=='allow')
      <li><a href='{{URL::to('module')}}' class="@yield('m_module')"><span>Module</span></a></li>
      @endif
  </ul>
</li>
@endif
@if(access_level_user('view','log')=='allow')
<li>
  <a href='{{URL::to('log')}}' class="@yield('class_log')"><i class='icon-clock-2'></i>
  <span>Log User Access</span>
</a>
</li>
@endif
  </ul>


</div>
</div>
</div>
