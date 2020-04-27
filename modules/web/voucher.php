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
					<h3><?php echo trans('voucher_details'); ?></h3>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered">
						<thead>
						<tr>
							<th class="text-center"><?php echo trans('serial_no');?></th>
							<th class="text-center"><?php echo trans('title');?></th>
							<th class="text-center"><?php echo trans('code');?></th>
							<th class="text-center"><?php echo trans('amount');?></th>
							<th class="text-center"><?php echo trans('availability');?></th>
							<th class="text-center"><?php echo trans('status');?></th>
						</tr>
						</thead>
						<tbody class="text-center">
						<?php 
						$count=1;
							$voucherInfo=app('admin')->getwhereand('as_voucher','user_id',$this->currentUser->id,'voucher_available','not_available');
							if(count($voucherInfo)==0)
								echo "<tr><td colspan='6'>".trans('no_data_found')."</td></tr>";
							else
							foreach($voucherInfo as $info){
						?>
							<tr>
								<td><?php echo $count;?></td>
								<td><?php echo $info['voucher_title'];?></td>
								<td><?php echo $info['voucher_code'];?></td>
								<td><?php echo $info['voucher_amount'];?></td>
								<td><?php 
									if($info['voucher_available']=='available'){?>
										<label class="label label-primary"><?php echo ucwords($info['voucher_available']); ?></label>
									<?php }else{ ?>
								<label class="label label-<?php if($info['voucher_available']=='not_available'){ echo "success";}else{ echo 'danger'; } ?>">
								<?php if($info['voucher_available']=='not_available'){ echo "Used";}else{ echo ucwords($info['voucher_available']); } ?></label>
								
							<?php } ?></td>
								<td>
								<?php 
									if($info['voucher_status']=='active'){?>
										<label class="label label-primary"><?php echo ucwords($info['voucher_status']); ?></label>
									<?php }else{ ?>
								<label class="label label-danger"><?php echo ucwords($info['voucher_status']); ?></label>
								
							<?php } ?>
								</td>
							</tr>
							<?php $count++;} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h3><?php echo trans('voucher');?></h3>
					
				</div>
				<div class="ibox-content VourcherSubmit">
					<form>
						<div class="form-group">
								<label class="control-label"><?php echo trans('gift_or_promocode');?> <span class="text-danger">*</span></label>
								<input type="text" class="form-control" placeholder="<?php echo trans("enter_gift_card_or_promocode");?>" name="voucher_code">
						</div>
						<div class="form-group">
							<p>By Clicking Redeem,you agree to the Gift Card & Promotion Code<a>Terms and Condition</a>.as applicable</p>
						</div>
						<div class="form-group">
							<button class="btn btn-primary" type="submit"><?php echo trans('redeem');?></button>
							<button class="btn btn-danger" type="reset"><?php echo trans('reset');?></button>
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
		$(".VourcherSubmit form").validate({
		rules:{
			voucher_code:{
				required: true
			},
		},
		submitHandler: function(form) {
			AS.Http.PostSubmit(form, {"action" : "GetVoucherSubmit"}, "ajax/", function (response) {
				if(response.status=='success'){
					swal({
						title: $_lang.success, 
						type: "success",
						confirmButtonColor: "#1ab394", 
						confirmButtonText: $_lang.ok,
						},function(isConfirm){
						location.reload();
					});
				}
				
			});
		}
	});
	</script>
[/footer]

<?php include dirname(__FILE__) .'/include/footer.php'; ?> 





