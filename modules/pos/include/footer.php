<?php defined('_AZ') or die('Restricted access'); ?>
<div class="create_ticket_modal"></div>
<div class="tutorial_modal"></div>
<div class="showError"></div>
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
	getJs('assets/system/js/jquery-3.1.1.min.js',false);
	getJs('assets/system/js/bootstrap.min.js',false);
	getJs('assets/system/js/plugins/metisMenu/jquery.metisMenu.js',false);
	getJs('assets/system/js/plugins/slimscroll/jquery.slimscroll.min.js',false);
	getJs('assets/system/js/plugins/jQuery.print.min.js',false);
	getJs('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js',false);
	getJs('assets/system/js/plugins/datapicker/bootstrap-datepicker.js');
	getJs('assets/system/js/plugins/easypiechart/jquery.easypiechart.js');
	getJs('assets/system/js/inspinia.js');
	getJs('assets/system/js/plugins/switchery/switchery.js');
	getJs('assets/js/vendor/popper.min.js',false);
	getJs('assets/js/vendor/jquery-validate/jquery.validate.min.js',false);
	getJs('assets/js/app/bootstrap.php',false,false);
	getJs('assets/js/app/common.js');
	getJs('assets/js/app/pos.js');
	getJs('assets/js/vendor/jquery.hotkeys.js',false);
	if (ASLang::getLanguage() != DEFAULT_LANGUAGE){
		echo getJs('assets/js/vendor/jquery-validate/localization/messages_'.ASLang::getLanguage().'.js',false);
	}
	getJs('assets/system/js/plugins/sweetalert/sweetalert.min.js');
	getJs('assets/system/js/plugins/toastr/toastr.min.js');
?>
<script>
	
	$(document).on("click",".logout", function(){
		AS.Http.post({"action" : "LogoutSubmit"}, "pos/ajax/", function (response) {
			if(response.status=='open'){
				swal({
					title: $_lang.registry_open,
					text: $_lang.are_sure_want_to_close_registry,
					type: "success",
					showCancelButton: true,
					confirmButtonColor: "#1ab394",
					confirmButtonText: $_lang.yes,
					cancelButtonText:$_lang.no ,
					closeOnConfirm: false,
					closeOnCancel: true 
				},
				function (isConfirm) {
					if (isConfirm) {
						location.href='logout/';
						} else {
						location.href='pos/registry';	
					}
				});
				}else{
				swal({
					title: $_lang.logout,
					text: "",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: $_lang.yes,
					cancelButtonText:$_lang.no ,
					closeOnConfirm: false,
					closeOnCancel: true 
					},function (isConfirm) {
					if (isConfirm) {
						location.href='logout/';
						} else {
						swal({
							title: $_lang.cancelled,
							text: "",
							type: "warning",
							confirmButtonColor: "#DD6B55",
							confirmButtonText: $_lang.ok,/*"Yes!"*/
						});
					}
				});
			}
		});
	});
	
	$(document).on('click','.create_ticket',function(){
        AS.Http.posthtml({"action" : "GetCreateTicketData"}, "pos/modal/", function (data) {
			
            $(".create_ticket_modal").html(data);
            $(".ticket_modal").modal("show");
		});
	});
	$(document).ready(function(){
		setInterval(function(){ AjaxCall() }, 10800000);
		// setTimeout(function() {
		// toastr.options = {
		// closeButton: true,
		// progressBar: true,
		// showMethod: 'slideDown',
		// timeOut: 4000
		// };
		// toastr.success('Point of Sale Software', 'Welcome to INSPINIA');
		
		// }, 1300);
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
	$(document).on('click','.help_btn',function(){
		var uri = window.location.pathname.split( '/' );
		jQuery.ajax({
			url: "ajax/",
			data: {
				action: "GetTutorialByPageName",
				page: uri[2],
			},
			type: "POST",
			success:function(res){
				if(res.status=='success'){
					AS.Http.posthtml({"action" : "GetTutorialByPageModal"}, "pos/modal/", function (data) {
						
      					$(".tutorial_modal").html(data);
						$(".video").attr('src',res.message);
						$(".show_tutorial_modal").modal("show");
					});
				}
				else{
					swal({
						title: "Not Found",
						text: res.message,
						icon: "success",
						button: "ok",
					});
					
				}
			},
			error:function (){}
		});
	});
	
	$(document).on('click','.changeStore',function(){
		jQuery.ajax({
			url: "ajax/",
			data: {
				action: "ChangeStoreByStoreId",
				id: $(this).data('store')
			},
			type: "POST",
			success:function(res){
				if(res.status == 'success'){
					location.reload();
				}
			},
			error:function (){}
		});
	});
</script>

<span id="company_name" data-company-name="<?php echo WEBSITE_NAME; ?>"></span>
</body>
</html>