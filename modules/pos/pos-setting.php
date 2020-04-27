<?php defined('_AZ') or die('Restricted access');
		include dirname(__FILE__) .'/include/header.php';
		include dirname(__FILE__) .'/include/side_bar.php';
		include dirname(__FILE__) .'/include/navbar.php';
		$getpossetting = app('admin')->getwhereid('pos_setting','id',1);
	?>
	<div class="poststatus"></div>
	<div class="wrapper wrapper-content animated fadeInRight" id="PostSettingForm">
		
		<div class="row">
			<div class="col-lg-6">
				<form class="form-horizontal" enctype="multipart/form-data">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h4><?php echo trans('pos_setting'); ?></h4>
						</div>
						<div class="ibox-content form-body">
							<div class="form-group">
								<label class="col-sm-3 control-label">
									<?php echo trans('company_name').' :'; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" readonly name="company_name" id="company_name" class="form-control" placeholder="<?php echo trans('your_company_name'); ?>" value="<?php echo $getpossetting['company_name']; ?>">
								</div>
							</div>
							<div class="hr-line-dashed"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label">
									<?php echo trans('company_email').' :'; ?>
								</label>
								<div class="col-sm-9">
									<input type="email" name="email" id="email" class="form-control" placeholder="<?php echo trans('email'); ?>" value="<?php echo $getpossetting['email']; ?>">
								</div>
							</div>
							<div class="hr-line-dashed"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label">
									<?php echo trans('company_phone').' :'; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="phone" id="phone" class="form-control" placeholder="<?php echo trans('phone'); ?>" value="<?php echo $getpossetting['phone']; ?>">
								</div>
							</div>
							<div class="hr-line-dashed"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label">
									<?php echo trans('company_address').' :'; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="address" id="address" class="form-control" placeholder="<?php echo trans('address'); ?>" value="<?php echo $getpossetting['address']; ?>">
								</div>
							</div>
							<div class="hr-line-dashed"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label">
									<?php echo trans('vat_registration_no').' :'; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="nbr_no" id="nbr_no" class="form-control" placeholder="<?php echo trans('nbr_registration_no'); ?>" value="<?php echo $getpossetting['nbr_no']; ?>">
								</div>
							</div>
							<div class="hr-line-dashed"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label">
									<?php echo trans('mushak').' :'; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="nbr_unit" id="nbr_unit" class="form-control" placeholder="<?php echo trans('nbr_unite'); ?>" value="<?php echo $getpossetting['nbr_unit']; ?>">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">
									<?php echo trans('currency').' :'; ?>
								</label>
								
								<div class="col-sm-9">
									<select class="form-control m-b" name="currency" id="currency">
										<option value="dfas">
											<?php echo trans('select_currency'); ?>
										</option>
										<?php $as_country = app('root')->select("SELECT * FROM `as_country`");
											foreach ($as_country as $value) {
												if(!empty($value['currency_symbol'])){
													if($getpossetting['currency'] == $value['code']){
														echo "<option value='".$value['code']."' selected>".$value['code'].' | '.$value['currency_symbol']."</option>";
														}else{
														echo "<option value='".$value['code']."'>".$value['code'].' | '.$value['currency_symbol']."</option>";
													}
												}
											}
										?>
									</select>
								</div>
							</div>
							
							<div class="hr-line-dashed"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label">
									<?php echo trans('vat_type').' :'; ?>
								</label>
								<div class="col-sm-9">
									<select name='vat_type' class="form-control" id="vat_type">
										<option>
											<?php echo trans('select_vat_type'); ?>
										</option>
										<option value='global' <?php if($getpossetting[ 'vat_type']=='global' ) echo 'selected'; ?>>
											<?php echo trans('global'); ?>
										</option>
										<option value='single' <?php if($getpossetting[ 'vat_type']=='single' ) echo 'selected'; ?>>
											<?php echo trans('single'); ?>
										</option>
									</select>
								</div>
							</div>
							
							<div class="form-group" id="var_area" style="display:<?php echo $getpossetting['vat_type'] == 'global' ? 'block' : 'none'; ?>;">
								<label class="col-sm-3 control-label">
									<?php echo trans('vat').'(%)'; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="vat" id="vat" class="form-control" value="<?php echo $getpossetting['vat']; ?>">
								</div>
							</div>
							
							<div class="hr-line-dashed"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label">
									<?php echo trans('receipt').' :'; ?>
								</label>
								<div class="col-sm-9">
									<textarea name="receipt" id="receipt" class="form-control"><?php echo $getpossetting['receipt_footer']; ?></textarea>
								</div>
							</div>
							
							<div class="hr-line-dashed"></div>
							<!--<div class="form-group">
								<label class="col-sm-3 control-label">
									<?php echo trans('invoice_margin_top').' :'; ?>
								</label>
								<div class="col-sm-3">
									<input type="text" name="invoice_margin_top" class="form-control" value="<?php echo $getpossetting['invoice_margin_top']; ?>">
								</div>
								<label class="col-sm-3 control-label">
									<?php echo trans('invoice_margin_bottom').' :'; ?>
								</label>
								<div class="col-sm-3">
									<input type="text" name="invoice_margin_bottom" class="form-control" value="<?php echo $getpossetting['invoice_margin_bottom']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">
									<?php echo trans('invoice_margin_left').' :'; ?>
								</label>
								<div class="col-sm-3">
									<input type="text" name="invoice_margin_left" class="form-control" value="<?php echo $getpossetting['invoice_margin_left']; ?>">
								</div>
								<label class="col-sm-3 control-label">
									<?php echo trans('invoice_margin_right').' :'; ?>
								</label>
								<div class="col-sm-3">
									<input type="text" name="invoice_margin_right" class="form-control" value="<?php echo $getpossetting['invoice_margin_right']; ?>">
								</div>
							</div>-->
							
							<div class="form-group">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-primary pull-right">
										<?php echo trans('save'); ?>
									</button>
								</div>
							</div>
							
						</div>
					</div>
				</form>
			</div>
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-12">
						<div class="ibox">
							<div class="ibox-title">
								<h5><?php echo trans('api_key_details'); ?></h5>
							</div>
							<div class="ibox-content table-responsive">
								<table class="table table-striped table-bordered">
									<thead>
										<tr>
											<td><?php echo trans('serial_number');?></td>
											<td><?php echo trans('api_key');?></td>
											<td><?php echo trans('pc_name');?></td>
											<td><?php echo trans('ip_address');?></td>
											<td><?php echo trans('status');?></td>
											<td><?php echo trans('action');?></td>
										</tr>
									</thead>
									<tbody>
									<?php 
									
										$GetApi = app("root")->table('as_pos_api')->where('domain_id',$this->currentUser->domain_id)->get();
										$count = 1;
										foreach($GetApi as $api){
									?>
										<tr>
											<td><?php echo $count;?></td>
										    <td><?php echo $api['api_key']; ?></td>
											<td><?php echo $api['pc_name'];?></td>
											<td><?php echo $api['last_ip'];?></td>
											<td>
												<?php 
												if($api['api_status'] == 'active'){
												?>
												<label class="label label-success"><?php echo $api['api_status'];?></label>
												<?php } else{ ?>
												<label class="label label-danger"><?php echo $api['api_status'];?></label>
												<?php } ?>
											</td>
											<td>
											<?php 
												if($api['api_status'] == 'active'){
											?>
												<button class="btn btn-danger btn-xs change_status"  data-id="<?php echo $api['id'];?>" data-status="inactive"><i class="fa fa-remove"></i></button>
											<?php } else{ ?>
												<button class="btn btn-primary btn-xs change_status" data-id="<?php echo $api['id'];?>" data-status="active"><i class="fa fa-check"></i></button>
											<?php } ?>
											</td>
										</tr>
										<?php
										$count++;
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	[footer]
	<script>
		$('#PostSettingForm form').validate({
			rules:{
				company_name: {
					required: true
				},
			},
			submitHandler: function(form) {
				// console.log(form['nbr_unit'].value);
				AS.Http.PostSubmit(form, {"action" : "PosSettingSubmit"}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						swal({
							title: $_lang.success, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
							},function (isConfirm) {
							location.reload();
						});
					}
				});
			}
		});
		
		document.getElementById('company_name').addEventListener('keypress', function(event) {
			if (event.keyCode == 13) {
				event.preventDefault();
			}
		});
		
		$(document).on('click','.change_status',function(){
				var id = $(this).data('id');
				var status = $(this).data('status');
				// alert(id);
				jQuery.ajax({
					url: "pos/ajax/",
					data: {
						action: "GetChangeApiStatus",
						api_id: id,
						api_status: status,
					},
					type: "POST",
					success:function(data){
						if(data.status){
							location.reload();
						}
						
					},
					error:function (){}
				});
		});
	</script>
	[/footer]
	<?php include dirname(__FILE__) .'/include/footer.php';?>																													