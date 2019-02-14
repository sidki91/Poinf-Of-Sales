<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    @include('layouts.header')
@if(Request::segment(1)=='pos')
<body class="widescreen pace-done fixed-left-void">
@else
<body class="fixed-left">
@endif
      <!-- Modal Start -->
        <!-- Modal Task Progress -->
<div class="md-modal md-3d-flip-vertical" id="task-progress">
  <div class="md-content">
    <h3><strong>Task Progress</strong> Information</h3>
    <div>
      <p>CLEANING BUGS</p>
      <div class="progress progress-xs for-modal">
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
        <span class="sr-only">80&#37; Complete</span>
        </div>
      </div>
      <p>POSTING SOME STUFF</p>
      <div class="progress progress-xs for-modal">
        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 65%">
        <span class="sr-only">65&#37; Complete</span>
        </div>
      </div>
      <p>BACKUP DATA FROM SERVER</p>
      <div class="progress progress-xs for-modal">
        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 95%">
        <span class="sr-only">95&#37; Complete</span>
        </div>
      </div>
      <p>RE-DESIGNING WEB APPLICATION</p>
      <div class="progress progress-xs for-modal">
        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
        <span class="sr-only">100&#37; Complete</span>
        </div>
      </div>
      <p class="text-center">
      <button class="btn btn-danger btn-sm md-close">Close</button>
      </p>
    </div>
  </div>
</div>

<!-- Modal Logout -->
<div class="md-modal md-just-me" id="logout-modal">
  <div class="md-content">
    <h3><strong>Logout</strong> Confirmation</h3>
    <div>
      <p class="text-center">Are you sure want to logout from this awesome system?</p>
      <p class="text-center">
      <button class="btn btn-danger md-close">Nope!</button>

      <a href="{{ url('/logout') }}"
          onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();" class="btn btn-success md-close">
          Yeah, I'm sure
      </a>
      <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
          {{ csrf_field() }}
      </form>
      </p>
    </div>
  </div>
</div>        <!-- Modal End -->
<!-- Begin page -->
@if(Request::segment(1)=='pos')
<div id="wrapper" class="forced enlarged">
@else
<div id="wrapper">
@endif
  @include('layouts.navigation')
  @include('layouts.menu')
  @include('layouts.rightmenu')

  <!-- Start right content -->
  @if(Request::segment(1)=='pos')
  @yield('content')
  @else
<div class="content-page">
<!-- ============================================================== -->
<!-- Start Content here -->
<!-- ============================================================== -->
<div class="content">
<div class="page-heading">
        <h3>

          @if($__env->yieldContent('firstmenu'))
            <span class="glyphicon glyphicon-th-large"></span> @yield('firstmenu')
          @endif
          @if($__env->yieldContent('menu'))
            <span class="glyphicon glyphicon-chevron-right"></span> @yield('menu')
          @endif
          @if($__env->yieldContent('sub_menu'))
            <span class="glyphicon glyphicon-chevron-right"></span> @yield('sub_menu')
          @endif
        </h3>
</div>
  <div class="row">
      <div class="col-lg-12 portlets">
          <div id="website-statistics1" class="widget">
              <div class="widget-header transparent">
                  <h2><i class="icon-chart-line"></i> <strong>@yield('title')</strong></h2>
                  <div class="additional-btn">

                      <a href="#" class="widget-toggle"><i class="icon-down-open-2"></i></a>
                      <a href="#" class="widget-close"><i class="icon-cancel-3"></i></a>
                  </div>
              </div>
              <div class="widget-content">
                  <div id="website-statistic" class="statistic-chart">
                      <div class="row stacked">
                          <div class="col-sm-12">
                              <div class="toolbar">

                                  <div class="clearfix"></div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="widget">
                                      <div class="widget-content padding">
                                          <div id="horizontal-form">
                                                @yield('content')
                                          </div>
                                      </div>
                                  </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
</div>
@endif

      @include('layouts.footer')
</body>
</html>
