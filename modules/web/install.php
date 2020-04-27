<?php 
	defined('_AZ') or die('Restricted access');
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	// 	include dirname(__FILE__) .'/include/navbar.php';
	$sub_dom_check = app('admin')->getwhereid('as_sub_domain','user_id',$this->currentUser->id);
	// if($sub_dom_check){
	// redirect($protocol.$sub_dom_check['sub_domain'].'.'.$sub_dom_check['root_domain'].'/pos/pos-setting');
	// }
	
?>
<div class="wrapper wrapper-content animated fadeInRight posSubscribe">
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4 start_msg">
			<div class="ibox-content">
				<div class="ibox-content">
					<div class="spiner-example text-center">
						<h3>POS Installation Start Within 10 Sec. Please do not close your browser.</h3>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-4 col-sm-offset-4 process_msg">
			<div class="ibox-content">
				<div class="ibox-content">
					<div class="spiner-example text-center">
						<div class="sk-spinner sk-spinner-rotating-plane"></div>
						<h2>Installation is in progress.....</h2>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-4 col-sm-offset-4 error_msg">
			<div class="ibox-content">
				<div class="ibox-content">
					<div class="spiner-example text-center">
						<h2 class="error_msg_show">Installation Incomplete.</h2>
						<button class="btn btn-danger btn-sm retry_now">Try Reinstall</button>
						
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-4 col-sm-offset-4 complete_msg">
			<div class="ibox-content">
				<div class="ibox-content">
					<div class="spiner-example text-center">
						<h2>Installation Complete</h2>
						<?php 
							$getsubdomain = app('admin')->getwhereid('as_sub_domain','user_id',$this->currentUser->id);
						?>
						<a href="<?php echo '//'.$getsubdomain['sub_domain'].'.'.$getsubdomain['root_domain'].'/pos/' ?>"><label class="label label-success">Go To Pos Dashboard</label></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
[footer]
<script>
	$(document).ready(function(){
		$(".start_msg").removeClass('hidden');
		$(".error_msg").addClass('hidden');
		$(".complete_msg").addClass('hidden');
		$(".process_msg").addClass("hidden");
	});
	
	setTimeout(function(){
		installProcess();
	}, 10000);
	
	function installProcess(){
		$(".start_msg").addClass('hidden');
		$(".error_msg").addClass('hidden');
		$(".process_msg").removeClass("hidden");
		
		AS.Http.post({
			action: "PosInstallation"
			},"ajax/",function(data){
			if(data['status']=='success'){
				$(".process_msg").addClass("hidden");
				$(".error_msg").addClass("hidden");
				$(".complete_msg").removeClass('hidden');
				}else if(data['status']=='error'){
				$(".process_msg").addClass("hidden");
				$(".complete_msg").addClass("hidden");
				$(".error_msg").removeClass('hidden');
				$(".error_msg_show").html(data['message']);
			}
		});
		
	}
	
	$(".retry_now").click(function(){
		installProcess();
	});
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php'; ?>