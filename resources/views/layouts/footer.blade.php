<script>
function alertMsg(message,clasStyle)
{
  $.notify({
      title: 'Info System',
      text: message,
      image: "<i class='fa fa-warning'></i>"
  }, {
      style: 'metro',
      className: clasStyle,
      globalPosition:'top right',
      showAnimation: "show",
      showDuration: 0,
      hideDuration: 0,
      autoHideDelay: 3000,
      autoHide: true,
      clickToHide: true
  });

}
</script>

<!-- Footer Start -->
<footer>
<div class="footer-links pull-right">

<a href="#">About</a><a href="#">Support</a><a href="#">Terms of Service</a><a href="#">Legal</a><a href="#">Help</a><a href="#">Contact Us</a>
</div>
</footer>
<!-- Footer End -->
</div>
<!-- ============================================================== -->
<!-- End content here -->
<!-- ============================================================== -->

</div>
<!-- End right content -->

</div>
<div id="contextMenu" class="dropdown clearfix">
<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
<li><a tabindex="-1" href="javascript:;" data-priority="high"><i class="fa fa-circle-o text-red-1"></i> High Priority</a></li>
<li><a tabindex="-1" href="javascript:;" data-priority="medium"><i class="fa fa-circle-o text-orange-3"></i> Medium Priority</a></li>
<li><a tabindex="-1" href="javascript:;" data-priority="low"><i class="fa fa-circle-o text-yellow-1"></i> Low Priority</a></li>
<li><a tabindex="-1" href="javascript:;" data-priority="none"><i class="fa fa-circle-o text-lightblue-1"></i> None</a></li>
</ul>
</div>
<!-- End of page -->
<!-- the overlay modal element -->
<div class="md-overlay"></div>
<!-- End of eoverlay modal -->
<script>
var resizefunc = [];
</script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<script src="{{ URL::asset('public/assets/libs/jquery/jquery-1.11.1.min.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/jqueryui/jquery-ui-1.10.4.custom.min.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/jquery-ui-touch/jquery.ui.touch-punch.min.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/jquery-detectmobile/detect.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/jquery-animate-numbers/jquery.animateNumbers.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/ios7-switch/ios7.switch.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/fastclick/fastclick.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/jquery-blockui/jquery.blockUI.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/bootstrap-bootbox/bootbox.min.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/jquery-slimscroll/jquery.slimscroll.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/jquery-sparkline/jquery-sparkline.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/nifty-modal/js/classie.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/nifty-modal/js/modalEffects.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/sortable/sortable.min.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/bootstrap-fileinput/bootstrap.file-input.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/bootstrap-select2/select2.min.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/pace/pace.min.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/jquery-notifyjs/notify.min.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/jquery-notifyjs/styles/metro/notify-metro.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{ URL::asset('public/assets/libs/jquery-icheck/icheck.min.js')}}"></script>

<!-- Chat Loader -->
<!-- <script src="assets/js/apps/chat.js"></script> -->

<!-- Demo Specific JS Libraries -->
<script src="{{ URL::asset('public/assets/libs/prettify/prettify.js')}}"></script>

<script src="{{ URL::asset('public/assets/js/init.js')}}"></script>
<!-- Page Specific JS Libraries -->
	<script src="{{ URL::asset('public/assets/libs/jquery-notifyjs/notify.min.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/jquery-notifyjs/styles/metro/notify-metro.js')}}"></script>
	<script src="{{ URL::asset('public/assets/js/pages/notifications.js')}}"></script>
  <script src="{{ URL::asset('public/assets/js/sweetalert.js')}}"></script>
  <script src="{{ URL::asset('public/assets/js/jquery.nicescroll.min.js')}}"></script>
  <script src="{{ URL::asset('public/assets/js/jquery.maskMoney.js')}}"></script>
  <script src="{{ URL::asset('public/assets/js/bootbox.min.js')}}"></script>
  
  <script type="text/javascript">
  // $(document).ready(function() {
  //   $("html").niceScroll({cursorwidth: '10px', autohidemode: true, zindex: 999 });
  // });

  function bersihPemisah(ini)
  {
      a = ini.toString().replace(",","");
      return a;
  }


  </script>

<!-- Page Specific JS Libraries -->
<!-- <script src="assets/libs/d3/d3.v3.js"></script>
<script src="assets/libs/rickshaw/rickshaw.min.js"></script>
<script src="assets/libs/raphael/raphael-min.js"></script>
<script src="assets/libs/morrischart/morris.min.js"></script>
<script src="assets/libs/jquery-knob/jquery.knob.js"></script>
<script src="assets/libs/jquery-jvectormap/js/jquery-jvectormap-1.2.2.min.js"></script>
<script src="assets/libs/jquery-jvectormap/js/jquery-jvectormap-us-aea-en.js"></script>
<script src="assets/libs/jquery-clock/clock.js"></script>
<script src="assets/libs/jquery-easypiechart/jquery.easypiechart.min.js"></script>
<script src="assets/libs/jquery-weather/jquery.simpleWeather-2.6.min.js"></script>
<script src="assets/libs/bootstrap-xeditable/js/bootstrap-editable.min.js"></script>
<script src="assets/libs/bootstrap-calendar/js/bic_calendar.min.js"></script>
<script src="assets/js/apps/calculator.js"></script>
<script src="assets/js/apps/todo.js"></script>
<script src="assets/js/apps/notes.js"></script>
<script src="assets/js/pages/index.js"></script> -->
