<?php 
	defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
?>
[header]
<style>
	.current_balance_amount{color: #18a689;font-weight:bold;}
</style>
[/header]
<div class="wrapper wrapper-content animated fadeInRight gray-bg">
	<div class="row">
		<div class="col-sm-8">
			<div class="ibox">
				<div class="ibox-title">
					<h3><?php echo trans('withdrawal_details'); ?></h3>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th class="text-center"><?php echo trans('serial_number');?></th>
								<th class="text-center"><?php echo trans('amount');?></th>
								<th class="text-center"><?php echo trans('charge');?></th>
								<th class="text-center"><?php echo trans('method');?></th>
								<th class="text-center"><?php echo trans('status');?></th>
								<th class="text-center"><?php echo trans('approved_by');?></th>
								<th class="text-center"><?php echo trans('note');?></th>
								<th class="text-center"><?php echo trans('action');?></th>
							</tr>
						</thead>
						<tbody class="text-center">
							<?php 
								$count=1;
								$withdrawal_info=app('admin')->getwhere('as_withdrawal','user_id',$this->currentUser->id);
								if(count($withdrawal_info)==0)
								echo "<tr><td colspan='8'>".trans('no_data_found')."</td></tr>";
								else
								foreach($withdrawal_info as $info)
								{
								?>
								<tr>
									<td><?php echo $count;?></td>
									<td><?php echo $info['withdrawal_amount'];?></td>
									<td><?php echo $info['withdrawal_charge'];?></td>
									<td><?php echo $info['withdrawal_method'];?></td>
									<td>
										<?php if($info['withdrawal_status']=='paid'){ ?>
											<label class="label label-success"><?php echo ucwords($info['withdrawal_status']); ?></label>
											<?php }else if($info['withdrawal_status']=='hold'){ ?>
											<label class="label label-warning"><?php echo ucwords($info['withdrawal_status']); ?></label>
											<?php }else{ ?>
											<label class="label label-danger"><?php 
												if($info['withdrawal_status']=="cancel")
												echo "Cancel Requsted";
												else
												echo ucwords($info['withdrawal_status']);
											?></label>
										<?php } ?>
									</td>
									<td><?php echo $info['withdrawal_approve_by'];?></td>
									<td><?php echo $info['withdrawal_note'];?></td>
									<td>
										<?php 
											if($info['withdrawal_status'] == "cancel"){
											?>
											<button class="btn btn-success btn-sm"><?php echo trans('review');?></button>
											<?php } else{ ?>
										<button class="btn btn-danger btn-sm cancel_withdrawal" w-id="<?php echo $info['withdrawal_id'];?>"><?php echo trans('cancel');?></button></td>
									<?php } ?>
								</tr>
							<?php $count++;}?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h3><?php echo trans('withdrawal');?>
						<span class="pull-right">
							<?php echo trans('current_balance');?>:
							<span class="current_balance_amount"><?php echo $currentFund['current_balance']; ?> Tk</span>
							
						</span>
					</h3>
					
				</div>
				<div class="ibox-content WithdrawalSubmit">
					<form>
						<div class="form-group">
							<label class="control-label"><?php echo trans('withdrawal_amount');?><span class="text-danger">*</span></label>
							<input type="text" class="form-control" placeholder="<?php echo trans("enter_withdrawal_amount");?>" name="withdrawal_amount">
						</div>
						<div class="form-group">
							<label class="control-label"><?php echo trans('withdrawal_method');?><span class="text-danger">*</span></label>
							<select class="form-control withdrawal_method" name="withdrawal_method">
								<?php $getPaymentMethod = app('admin')->getwhere('as_payment_method','status','active');
									foreach($getPaymentMethod as $method){
									?>
									<option value="<?php echo $method['payment_method_value']; ?>"><?php echo $method['payment_method_name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="mobile_account_info hide">
							<h3><span class="withdrawal_method_name"></span>  Account Information</h3>
							<div class="form-group">
								<label class="control-label"><?php echo trans('account_number');?> <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="account_number" placeholder="<?php echo trans('type_your_account_number');?>">
							</div>
							<div class="form-group">
								<label class="control-label"><?php echo trans('account_type');?><span class="text-danger">*</span></label>
								<select class="form-control" name="account_type">
									<option value="personal">Personal</option>
									<option value="agent">Agent</option>
								</select>
							</div>
						</div>
						<div class="bank_account_info hide">
							<h3><span class="withdrawal_method_name"></span>  Account Information</h3>
							<div class="form-group">
								<label class="control-label"><?php echo trans('account_number');?> <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="bank_account_number" placeholder="<?php echo trans('type_your_account_number');?>" value="">
							</div>
							<div class="form-group">
								<label class="control-label"><?php echo trans('withdrawal_branch');?><span class="text-danger">*</span></label>
								<input type="text" class="form-control" placeholder="<?php echo trans('type_withdrawal_branch_name');?>" name="withdrawal_branch" value="">
							</div>
						</div>
						<div class="form-group">
							<button class="btn btn-primary" type="submit"><?php echo trans('withdrawal');?></button>
							<button class="btn btn-danger" type="button"><?php echo trans('reset');?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>           
</div>
<div class="ModalForm">
	<div class="modal_status"></div>
</div>  
[footer]
<script>
	$(".withdrawal_method").change(function(){
		var method = $(this).val();
		$(".withdrawal_method_name").html(ucwords(method));
		$(".mobile_account_info").addClass('hide');
		$(".bank_account_info").addClass('hide');
		if(method == 'office'){
			
		}
		else if(method == 'bank' && method!='office'){
			$(".bank_account_info").removeClass('hide');
			}else{
			$(".mobile_account_info").removeClass('hide');
		}
	});
	$(".WithdrawalSubmit form").validate({
		rules:{
			withdrawal_amount:{
				required: true
			},
			withdrawal_method:{
				required: true
			},
			account_number:{
				required: true
			},
			bank_account_number:{
				required: true
			},
			account_type:{
				required: true
			},
			withdrawal_branch:{
				required: true
			},
		},
		submitHandler: function(form) {
			
			AS.Http.PostSubmit(form, {"action" : "GetWithdrawalSubmit"}, "ajax/", function (response) {
				swal({
					title: response.status, 
					text: response.message,
					type: response.status,
					confirmButtonColor: "#1ab394", 
					confirmButtonText: $_lang.ok,
					},function(isConfirm){
					if(response.status=='success'){
						location.reload();
					}
				});
			});
		}
	});
	$(".cancel_withdrawal").click(function(){
		var withdrawal_id = $(this).attr('w-id');
		$.ajax({
			url:"ajax/",
			data:
			{
				action:"WithdrawalCancel",
				id:withdrawal_id,
			},
			success:function(res){
				if(res.status == "success"){
					location.reload();
				}
			},
		});
	});
</script>
[/footer]

<?php include dirname(__FILE__) .'/include/footer.php'; ?> 





