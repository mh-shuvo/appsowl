<?php 
	defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	echo getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);	
?>
<div class="wrapper wrapper-content animated fadeInRight gray-bg">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class = "ibox-title">
					<h3><?php echo trans('subscription_history'); ?></h3>
				</div>
				<div class="ibox-content">					
					<div>							
						<table class="table table-striped table-bordered text-center dataTables-example" id="">
							<thead>
								<tr role="row">
									<th class="text-center"><?php echo trans('subscribe_id'); ?></th>
									<th class="text-center"><?php echo trans('subscribe_date'); ?></th>
									<th class="text-center"><?php echo trans('subscribe_start_date'); ?></th>
									<th class="text-center"><?php echo trans('subscribe_end_date'); ?></th>
									<th class="text-center"><?php echo trans('activation_date'); ?></th>
									<th class="text-center"><?php echo trans('renew_date'); ?></th>
									<th class="text-center"><?php echo trans('title'); ?></th>  
									<th class="text-center"><?php echo trans('type'); ?></th>
									<th class="text-center"><?php echo trans('billing_terms'); ?></th> 
									<th class="text-center"><?php echo trans('amount'); ?></th> 
									<th class="text-center"><?php echo trans('status'); ?></th> 
									<th class="text-center"><?php echo trans('action'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$count=1;
									$subscribe_details = app('admin')->getwhere('as_subscribe','user_id',ASSession::get("user_id"));
									foreach($subscribe_details as $subscribe){
										$software = app('admin')->getwhereid('as_software','software_id',$subscribe['software_id']);
										$checkBillingType= $software['software_billing_type'];
										$invoice_data = app('root')->select("SELECT `invoice_id`,`subscribe_id`,`invoice_status`,`subscribe_start_date`,`subscribe_end_date`,`invoice_amount` FROM `as_invoices` WHERE `invoice_type` = 'bill' AND `subscribe_id` = :id ORDER BY `invoice_id` DESC LIMIT 1", 
										array( 'id' => $subscribe['subscribe_id']));
										
										if(!$invoice_data){
											if($checkBillingType == 'postpaid'){
												$date = new DateTime($subscribe['subscribe_activation_date']);
												$date->modify('+ 1 month');
												$invoice_data[0]['subscribe_end_date'] = $date->format('Y-m-d');
												$invoice_data[0]['subscribe_start_date']=$subscribe['subscribe_activation_date'];
												$invoice_data[0]['invoice_amount']=$subscribe['subscribe_amount'];
											}
										}
										if($subscribe['subscribe_type']=='software'){
											$software_variaton=app('admin')->getwhereid('as_software_variation','software_variation_id',$subscribe['software_variation_id']);
											$software=app('admin')->getwhereid('as_software','software_id',$subscribe['software_id']);
											$title=$software['software_title'].'['.$software_variaton['software_variation_name'].']';
										}
										else if($subscribe['subscribe_type']=='plugins'){
											$plugins=app('admin')->getwhereid('as_plugins','plugins_id',$subscribe['plugins_id']);
											$title=$plugins['plugins_name'];
										}
										else{
											$service=app('admin')->getwhereid('as_services','service_id',$subscribe['service_id']);
											$title = $service['service_name'];
										}
									?>
									<tr>
										<td><?php echo $subscribe['subscribe_id']; ?></td>
										<td><?php 
											echo getdatetime($subscribe['subscribe_date'],3);
										?></td>
										<td>
											<?php 
												if(!$invoice_data){
													if($checkBillingType != 'postpaid'){
														echo "Not Available";
													}
												}
												else{
													echo getdatetime($invoice_data[0]['subscribe_start_date'],3); 
												}
											?>
										</td>
										<td>
											<?php
												if(!$invoice_data){
													if($checkBillingType != 'postpaid'){
														echo "Not Available";
													}
												}
												else{
													echo getdatetime($invoice_data[0]['subscribe_end_date'],3); 
												}
											?>
										</td>
										<td>
											<?php echo getdatetime($subscribe['subscribe_activation_date'],3); ?>
										</td>
										<td>
											<?php
												if(!$invoice_data){
													if($checkBillingType != 'postpaid'){
														echo "Not Available";
													}
												}
												else{
													echo getdatetime($invoice_data[0]['subscribe_end_date'],3);
												}
											?>
										</td>
										<td>
											<?php echo $title; ?>
										</td>
										<td>
											<?php echo ucwords($subscribe['subscribe_type']);?>
										</td>
										<td><?php
											if($subscribe['subscribe_payment_terms']!=null)
											echo $subscribe['subscribe_payment_terms_value'].' Months'; 
											else
											echo '0'; 
										?>
										</td>
										<td>
											<?php
												if(!$invoice_data){
													if($checkBillingType != 'postpaid'){
														echo "Not Available";
													}
												}
												else{
													echo $invoice_data[0]['invoice_amount'];
												}
											?>
										</td>
										<td>
											<span class="label <?php echo ($subscribe['subscribe_status'] == 'active' ? 'label-primary' : 'label-danger');?>">
												<?php
													echo strtoupper($subscribe['subscribe_status']);
												?>
											</span>
										</td>
										<td>
											<div class="btn-group">
												<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
												<ul class="dropdown-menu pull-right" role="menu">
													<?php if($subscribe['subscribe_status'] == 'expire'){ ?>
														<li>
															<a href="javascript:void(0);" 
															data-subscribe-id="<?php echo $subscribe['subscribe_id']; ?>"
															class="payment_modal">
																<i class="fa fa-edit"></i><?php echo trans('pay_now');?>
															</a>
														</li>
													<?php } ?>
													<li>
														<a href="payment-history" 
														id="<?php echo $subscribe['subscribe_id']; ?>">
															<i class="fa fa-history"></i> 
															<?php echo trans('payment_history');?>
														</a>
													</li>
													<?php if($subscribe['subscribe_type'] == 'software'){ ?>
														<li>
															<a href="module/<?php echo $subscribe['software_id']; ?>" 
															id="<?php echo $subscribe['subscribe_id']; ?>">
																<i class="fa fa-history"></i> 
																<?php echo trans('module');?>
															</a>
														</li>
													<?php  } ?>
													<li>
														<a href="javascript:void(0)" data-subscribe-id="<?php echo $subscribe['subscribe_id']; ?>" id="<?php echo $subscribe['subscribe_id']; ?>" class="sub_cancel">
															<i class="fas fa-strikethrough"></i>
															<?php echo trans('cancel');?>
														</a>
													</li>
												</ul>
											</div>
										</td>
									</tr>
								<?php }?>
							</tbody>
							<tfoot>
								
								</tfoot>
								</table>
							</div>
							
				</div>
				
			</div>
		</div>
	</div>           
</div>
<div class="ModalForm">
	<div class="modal_status"></div>
</div>  
[footer]
<?php echo getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false); ?>
<script>
	
	$(document).on("click",".sub_cancel", function(){
	    
	    swal({
    		title: $_lang.cancel,
    		text: $_lang.subscription_cancel_message,
    		type: "warning",
    		showCancelButton: true,
    		confirmButtonColor: "#DD6B55",
    		confirmButtonText: $_lang.yes,/*"Yes!"*/
    		cancelButtonText:$_lang.no ,/*"No!"*/
    		closeOnConfirm: false,
    	closeOnCancel: false },
    	function (isConfirm) {
    		if (isConfirm) {
    			var subscribeId = $(this).data('subscribe-id');
        		AS.Http.post({"action" : "getSubscribeCancel","subscribe_id":subscribeId}, "ajax/", function (data) {
        			swal({
        				title: data['title'], 
        				text: data['massage'], 
        				type: data['status'],
        				confirmButtonColor: "#1ab394", 
        				confirmButtonText: $_lang.ok,
        				},function(isConfirm){
        				if(data['status']=='success'){
        					location.reload();
        				}
        			});
        		});
    			} else {
    			swal(

    			{
    				title: $_lang.cancelled,
    				text: "",
    				type: "warning",
    				confirmButtonColor: "#DD6B55",
    				confirmButtonText: $_lang.ok,/*"Yes!"*/
    			}
    			); /*"বাতিল করা হয়েছে"*/
    		}
    	});
		
	});
	$(document).ready(function(){
		$('.dataTables-example').DataTable({
			pageLength: 25,
			responsive: true,
			dom: '<"html5buttons"B>lTfgitp',
			buttons: [
			{extend: 'excel', title: 'purchase_report'},
			{extend: 'pdf', title: 'purchase_report'},
			
			{extend: 'print',
				customize: function (win){
					$(win.document.body).addClass('white-bg');
					$(win.document.body).css('font-size', '10px');
					
					$(win.document.body).find('table')
					.addClass('compact')
					.css('font-size', 'inherit');
				}
			}
			]
			
		});
	});
	$(document).on("click",".payment_modal", function(){
		var subscribeId = $(this).data('subscribe-id');
		AS.Http.post({"action" : "getManualPayment","subscribe_id":subscribeId}, "ajax/", function (data) {
			swal({
				title: data['title'], 
				text: data['massage'], 
				type: data['status'],
				confirmButtonColor: "#1ab394", 
				confirmButtonText: $_lang.ok,
				},function(isConfirm){
				if(data['status']=='success'){
					location.reload();
				}
			});
		});
	});
	
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php'; ?> 





