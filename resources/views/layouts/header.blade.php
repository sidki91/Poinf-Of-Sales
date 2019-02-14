<head>
     <meta charset="UTF-8">
     <title>Dashboard | Coco - Responsive Bootstrap Admin Template</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
     <meta name="apple-mobile-web-app-capable" content="yes" />
     <meta name="description" content="">
     <meta name="keywords" content="coco bootstrap template, coco admin, bootstrap,admin template, bootstrap admin,">
     <meta name="author" content="Huban Creative">

     <!-- Base Css Files -->
     <link href="{{ URL::asset('public/assets/libs/jqueryui/ui-lightness/jquery-ui-1.10.4.custom.min.css') }}" rel="stylesheet" />
     <link href="{{ URL::asset('public/assets/libs/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />
     <link href="{{ URL::asset('public/assets/libs/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
     <link href="{{ URL::asset('public/assets/libs/fontello/css/fontello.css')}}" rel="stylesheet" />
     <link href="{{ URL::asset('public/assets/libs/animate-css/animate.min.css')}}" rel="stylesheet" />
     <link href="{{ URL::asset('public/assets/libs/nifty-modal/css/component.css')}}" rel="stylesheet" />
     <link href="{{ URL::asset('public/assets/libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" />
     <link href="{{ URL::asset('public/assets/libs/ios7-switch/ios7-switch.css')}}" rel="stylesheet" />
     <link href="{{ URL::asset('public/assets/libs/pace/pace.css')}}" rel="stylesheet" />
     <link href="{{ URL::asset('public/assets/libs/sortable/sortable-theme-bootstrap.css')}}" rel="stylesheet" />
     <link href="{{ URL::asset('public/assets/libs/bootstrap-datepicker/css/datepicker.css')}}" rel="stylesheet" />
     <link href="{{ URL::asset('public/assets/libs/jquery-icheck/skins/all.css')}}" rel="stylesheet" />
     <link href="{{ URL::asset('public/assets/libs/jquery-notifyjs/styles/metro/notify-metro.css')}}" rel="stylesheet" type="text/css" />

     <!-- Code Highlighter for Demo -->
     <link href="{{ URL::asset('public/assets/libs/jquery-notifyjs/styles/metro/notify-metro.css')}}" rel="stylesheet" type="text/css" />

     <link href="{{ URL::asset('public/assets/libs/prettify/github.css')}}" rel="stylesheet" />

             <!-- Extra CSS Libraries Start -->
             <link href="{{ URL::asset('public/assets/libs/rickshaw/rickshaw.min.css')}}" rel="stylesheet" type="text/css" />
             <link href="{{ URL::asset('public/assets/libs/morrischart/morris.css')}}" rel="stylesheet" type="text/css" />
             <link href="{{ URL::asset('public/assets/libs/jquery-jvectormap/css/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />
             <link href="{{ URL::asset('public/assets/libs/jquery-clock/clock.css')}}" rel="stylesheet" type="text/css" />
             <link href="{{ URL::asset('public/assets/libs/bootstrap-calendar/css/bic_calendar.css')}}" rel="stylesheet" type="text/css" />
             <link href="{{ URL::asset('public/assets/libs/sortable/sortable-theme-bootstrap.css')}}" rel="stylesheet" type="text/css" />
             <link href="{{ URL::asset('public/assets/libs/jquery-weather/simpleweather.css')}}" rel="stylesheet" type="text/css" />
             <link href="{{ URL::asset('public/assets/libs/bootstrap-xeditable/css/bootstrap-editable.css')}}" rel="stylesheet" type="text/css" />
             <link href="{{ URL::asset('public/assets/css/style.css')}}" rel="stylesheet" type="text/css" />
             <!-- Extra CSS Libraries End -->
             <link href="{{ URL::asset('public/assets/css/style-responsive.css')}}" rel="stylesheet" />
            <link rel="shortcut icon" href="{{ URL::asset('public/assets/img/favicon.ico')}}">
             <link rel="apple-touch-icon" href="{{ URL::asset('public/assets/img/apple-touch-icon.png')}}" />
             <link rel="apple-touch-icon" sizes="57x57" href="{{ URL::asset('public/assets/img/apple-touch-icon-57x57.png')}}" />
             <link rel="apple-touch-icon" sizes="72x72" href="{{ URL::asset('public/assets/img/apple-touch-icon-72x72.png')}}" />
             <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('public/assets/img/apple-touch-icon-76x76.png')}}" />
             <link rel="apple-touch-icon" sizes="114x114" href="{{ URL::asset('public/assets/img/apple-touch-icon-114x114.png')}}" />
             <link rel="apple-touch-icon" sizes="120x120" href="{{ URL::asset('public/assets/img/apple-touch-icon-120x120.png')}}" />
             <link rel="apple-touch-icon" sizes="144x144" href="{{ URL::asset('public/assets/img/apple-touch-icon-144x144.png')}}" />
             <link rel="apple-touch-icon" sizes="152x152" href="{{ URL::asset('public/assets/img/apple-touch-icon-152x152.png')}}" />
             <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
          

<!-- Scripts -->
<script>
    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
    ]); ?>



</script>
<style>
#pagination div { display: inline-block; margin-right: 5px; margin-top: 5px }
#pagination .cell a { border-radius: 3px; font-size: 11px; color: #333; padding: 8px; text-decoration:none; border: 1px solid #d3d3d3; background-color: #f8f8f8; }
#pagination .cell a:hover { border: 1px solid #c6c6c6; background-color: #f0f0f0;  }
#pagination .cell_active span { border-radius: 3px; font-size: 11px; color: #333; padding: 8px; border: 1px solid #c6c6c6; background-color: #e9e9e9; }
#pagination .cell_disabled span { border-radius: 3px; font-size: 11px; color: #777777; padding: 8px; border: 1px solid #dddddd; background-color: #ffffff; }

</style>

</head>
