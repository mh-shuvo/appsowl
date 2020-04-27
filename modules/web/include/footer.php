<?php defined('_AZ') or die('Restricted access'); ?>
<div class="footer">
	<div>
		<strong><?php echo trans('copyright'); ?></strong> <?php echo trans('software_galaxy'); ?> &copy;  <?php echo trans('year'); ?>
	</div>
</div>            
<!--</div>-->
</div>
<script>
    // var myVar = setInterval(function(){ myTimer() }, 1000);
	
    // function myTimer() {
        // var d = new Date();
        // var t = d.toLocaleTimeString();
        // document.getElementById("time_now").innerHTML = t;
	// }
	
</script>
<?php
	echo getJs('assets/system/js/jquery-3.1.1.min.js',false,true);
	echo getJs('assets/system/js/bootstrap.min.js',false);
	echo getJs('assets/system/js/plugins/metisMenu/jquery.metisMenu.js');
	echo getJs('assets/system/js/plugins/slimscroll/jquery.slimscroll.min.js'.false);
	echo getJs('assets/system/js/plugins/flot/jquery.flot.js',false);
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
	echo getJs('assets/system/js/plugins/bootstrap-markdown/markdown.js',false,false);
	echo getJs('assets/system/js/plugins/summernote/summernote.min.js',false);
	echo getJs('assets/system/js/plugins/jasny/jasny-bootstrap.min.js',false);
?>
<script type="text/javascript">
	$("body").on('click','.customer_edit',function(){
		var customer_id = $(this).attr('customer_id');
		$("#customer_id").val(customer_id);
		var customer_name = $(this).attr('customer_name');
		$("#customer_name").val(customer_name);
		var customer_address = $(this).attr('customer_address');
		$("#customer_address").val(customer_address);
		var customer_phone = $(this).attr('customer_phone');
		$("#customer_phone").val(customer_phone);
		var customer_email = $(this).attr('customer_email');
		$("#customer_email").val(customer_email);
		var customer_due = $(this).attr('customer_due');
		$("#customer_due").val(customer_due);
		
	});
	
	$('ul.nav li.dropdown').hover(function() {
	  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(50);
	}, function() {
	  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(50);
	});
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#data_1 .input-group.date').datepicker({
			todayBtn: "linked",
			keyboardNavigation: false,
			forceParse: false,
			calendarWeeks: true,
			autoclose: true
		});
		setInterval(function(){ AjaxCall() }, 10800000);
	});
	function AjaxCall()
	{
		var last_id = $('.notifications_area').attr('last_id');
		var user_id = $('.notifications_area').attr('user_id');
		jQuery.ajax({
			url: "ajax/",
			data: {
				action: "GetNotification",
				u_id: user_id,
				n_id: last_id,
			},
			type: "POST",
			success:function(res){
			if(res.status == 'success'){
				$.each(res.notifications, function(index, value) {				
				var li =  document.createElement('li');
				var a =  document.createElement('a');
				var title_link =  document.createElement('a');
				var div =  document.createElement('div');
				var outer_strong =  document.createElement('strong');
				var p =  document.createElement('p');
				var inner_strong =  document.createElement('strong');
				var br =  document.createElement('br');
				var small =  document.createElement('small');
				var divider = document.createElement('li');
				divider.setAttribute('class','divider');
				div.setAttribute('class','media-body');
				inner_strong.setAttribute('class','strong');
				small.setAttribute('class','text-muted');
				
				if(value.link!=null){
					a.setAttribute('href',value.link);
				}
				else{
					a.setAttribute('href','javascript:void(0)');
				}
				if(value.read_status=='unread'){
					a.setAttribute('class','change_read_status');
					li.setAttribute('style','background:#e7f3f6');
				}
				else{
					a.setAttribute('class','');
				}
				
				small_text = document.createTextNode(value.created_at);
				small.appendChild(small_text);
				
				inner_strong_text = document.createTextNode(value.message);
				inner_strong.appendChild(inner_strong_text);
				p.appendChild(inner_strong);
				
				outer_strong_text = document.createTextNode(value.title);
				outer_strong.appendChild(outer_strong_text);
				
				div.appendChild(outer_strong);
				div.appendChild(p);
				div.appendChild(small);
				
				a.appendChild(div);
				
				li.appendChild(a);
				
				$('.notifications_area').prepend(divider);
				$('.notifications_area').prepend(li);
				
				});
				$('.notifications_area').attr('last_id',res.last_id);
				var total = $(".total_notification").attr('total_notification');
				total = parseInt(total);
				$(".total_notification").attr('total_notification',total+res.total);
				$(".total_notification").html(total+res.total);
			}
			},
			error:function (){}
		});
	}
	 $(document).on('click','.change_read_status',function(){
		$(this).css('background','white'); 
		var id = $(this).attr('n_id');
		jQuery.ajax({
			url: "ajax/",
			data: {
				action: "UpdateNotificationReadStatus",
				n_id: id,
			},
			type: "POST",
			success:function(res){
			},
			error:function (){}
		});
	 });
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
		echo getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
	}
	echo getJs('assets/system/js/plugins/sweetalert/sweetalert.min.js',false);
?>
</body>

</html>
