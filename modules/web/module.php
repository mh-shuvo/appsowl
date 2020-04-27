<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	$GetPlugins=app('admin')->getall('as_plugins');
	
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-sm-12">
			<!-- App Pricing -->
			<div class="landing-page">
				<div class="row m-b-lg">
					<div class="col-lg-12 text-center">
						<div class="navy-line"></div>
						<h1><?php echo trans('module'); ?></h1>
						
					</div>
				</div>
				<div class="row">
					<?php 
						if($GetPlugins){
							foreach($GetPlugins as $plugin){
								if($plugin['plugins_status'] =='active'){
									$pluginActiveCheck = app('admin')->getwhereandid('as_subscribe','plugins_id',$plugin['plugins_id'],'user_id',$this->currentUser->id);
								?>
								<div class="col-sm-4">
									<div class="ibox">
										<div class="ibox-content">
											<div class="row">
												<div class="col-sm-4">
													<img class="img-responsive img-rounded" src="assets/system/img/module/<?php echo $plugin['plugins_image']; ?>" >
												</div>
												<div class="col-sm-8">
												<h4> <?php echo $plugin['plugins_name']; ?></h4>
													<p>
														<small style="font-weight:bold">V.<?php echo $plugin['plugins_version'];?></small> | 
														<small style="font-weight:bold"><?php echo ucwords($plugin['plugins_update_type']);?> Update</small> | 
														<small style="font-weight:bold"><?php echo ucwords($plugin['plugins_billing_type']);?></small> | 
													</p>
													
													<p><?php echo substr($plugin['plugins_details'],0,150); ?> </p>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-sm-6">
														<?php if($plugin['plugins_billing'] == 'monthly'){?>
															<span class="label label-success"><?php echo $plugin['plugins_price']; ?>TK/Month</span>
															<?php }elseif($plugin['plugins_billing'] == 'yearly'){?>
															<span class="label label-success"><?php echo $plugin['plugins_price']; ?>TK/Year</span>
															<?php }elseif($plugin['plugins_billing'] == 'onetime'){?>
															<span class="label label-success"><?php echo $plugin['plugins_price']; ?>TK/Onetime</span>
															<?php }else{?>
															<span class="label label-primary">Free</span>
														<?php }?>
												</div>
												<div class="col-sm-6">
													<?php 
														if($pluginActiveCheck){
															if($pluginActiveCheck['subscribe_status'] == 'expire'){
															?>
															<a type="button" class="btn btn-info btn-sm pull-right" href="history"><?php echo trans('expired');?></a>
															<?php }else if($pluginActiveCheck['subscribe_status'] == 'active'){?>
															<button type="button" class="btn btn-sm btn-danger module_status_change pull-right" status="inactive" plugin_id="<?php echo $plugin['plugins_id']; ?>"><?php echo trans('inactive_now');?></button>
															<?php }else{?>
															<button type="button" class="btn btn-sm btn-success module_status_change pull-right" status="active" plugin_id="<?php echo $plugin['plugins_id']; ?>"><?php echo trans('active_now');?></button>
															<?php 
															} 
															}else{ 
														?>
														<button type="button" class="btn btn-sm btn-warning module_status_change pull-right" status="active" plugin_id="<?php echo $plugin['plugins_id']; ?>"><?php echo trans('active_now');?></button>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php }}}else{?>
							<div class="col-sm-4 col-sm-offset-4">
								<div class="ibox">
									<div class="ibox-content">
										<h1 class="text-center"><?php echo trans('no_module_added');?></h1>
									</div>
								</div>
							</div>
					<?php }?>
					
				</div>
			</div>
		</div>
	</div>
</div>

[footer]

<script>
	$(".module_status_change").click(function(){
		var st= $(this).attr('status');
		var m_id= $(this).attr('plugin_id');
		jQuery.ajax({
			url: "ajax/",
			data: {
				action: "ModuleStatusChange",
				status: st,
				id: m_id
			},
			type: "POST",
			success:function(data){
				swal({
					title: data['status'], 
					text: data['massage'], 
					type: data['status'],
					confirmButtonColor: "#1ab394", 
					confirmButtonText: $_lang.ok,
					},function(isConfirm){
					if(data['status']=='success'){
						location.reload();
					}
				});
			},
			error:function (){}
		});
	});
	
	var elem = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	
	elem.forEach(function(html) {
		var switchery = new Switchery(html, { color: '#4680ff', jackColor: '#fff' });
	});
	
</script>
[/footer]
<?php
	getJs('assets/system/js/plugins/chosen/chosen.jquery.js',false,false); 
include dirname(__FILE__) .'/include/footer.php'; ?>	