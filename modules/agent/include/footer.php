<?php defined('_AZ') or die('Restricted access'); ?>
<div class="footer">
	<div>
		<strong><?php echo trans('copyright'); ?></strong> <?php echo trans('software_galaxy'); ?> &copy;  <?php echo trans('year'); ?>
	</div>
</div>            
</div>
</div>
<script>
    var myVar = setInterval(function(){ myTimer() }, 1000);
	
    function myTimer() {
        var d = new Date();
        var t = d.toLocaleTimeString();
        document.getElementById("time_now").innerHTML = t;
	}
</script>

<?php
echo getJs('assets/system/js/jquery-3.1.1.min.js',false);
echo getJs('assets/system/js/plugins/jQuery.print.min.js',false);
echo getJs('assets/system/js/bootstrap.min.js',false);
echo getJs('assets/system/js/plugins/metisMenu/jquery.metisMenu.js');
echo getJs('assets/system/js/plugins/slimscroll/jquery.slimscroll.min.js',false);
echo getJs('assets/system/js/plugins/flot/jquery.flot.js');
echo getJs('assets/system/js/plugins/flot/jquery.flot.tooltip.min.js',false);
echo getJs('assets/system/js/plugins/flot/jquery.flot.spline.js');
echo getJs('assets/system/js/plugins/flot/jquery.flot.resize.js');
echo getJs('assets/system/js/plugins/flot/jquery.flot.pie.js');
echo getJs('assets/system/js/plugins/peity/jquery.peity.min.js',false);
echo getJs('assets/system/js/demo/peity-demo.js');
echo getJs('assets/system/js/plugins/jquery-ui/jquery-ui.min.js',false);
echo getJs('assets/system/js/plugins/gritter/jquery.gritter.min.js',false);
echo getJs('assets/system/js/plugins/sparkline/jquery.sparkline.min.js',false);
echo getJs('assets/system/js/plugins/datapicker/bootstrap-datepicker.js');
echo getJs('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js',false);
echo getJs('assets/system/js/inspinia.js');
echo getJs('assets/system/js/plugins/pace/pace.min.js',false);
echo getJs('assets/system/js/plugins/bootstrap-markdown/bootstrap-markdown.js');
echo getJs('assets/system/js/plugins/bootstrap-markdown/markdown.js');
echo getJs('assets/system/js/plugins/summernote/summernote.min.js',false);
echo getJs('assets/system/js/plugins/jasny/jasny-bootstrap.min.js',false);
echo getJs('assets/system/js/plugins/easypiechart/jquery.easypiechart.js');
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#data_1 .input-group.date').datepicker({
			todayBtn: "linked",
			keyboardNavigation: false,
			forceParse: false,
			calendarWeeks: true,
			autoclose: true
		});
	})
</script>
<?php
echo getJs('assets/js/vendor/sha512.js');
echo getJs('assets/js/vendor/popper.min.js',false);
echo getJs('assets/js/vendor/jquery-validate/jquery.validate.min.js',false);
?>
<script src="assets/js/app/bootstrap.php"></script>
<?php
echo getJs('assets/js/app/common.js');
echo getJs('assets/js/app/admin.js');
if (ASLang::getLanguage() != DEFAULT_LANGUAGE){
echo getJs('assets/js/vendor/jquery-validate/localization/messages_'.ASLang::getLanguage().'.js');
echo getJs('assets/system/js/plugins/dataTables/datatables.min.js',false);
}
echo getJs('assets/system/js/plugins/sweetalert/sweetalert.min.js',false);
?>


</body>

</html>
