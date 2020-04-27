<?php 
	defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	if(isset($_GET['pid'])){
		$current_fund= app('admin')->getwhereandid('as_payment','user_id',$this->currentUser->id,'payment_id',$_GET['pid']);
	}
?>
[header]
<style>
	.current_balance_amount{color: #18a689;font-weight:bold;}
	th,tbody{text-align:center;}
</style>
[/header]
<div class="wrapper wrapper-content animated fadeInRight gray-bg">
	<div class="row">
		<div class="col-sm-8">
			<div class="ibox">
				<div class="ibox-title">
					<h3><?php echo trans('fund_details'); ?></h3>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th><?php echo trans('serial_number');?></th>
								<th><?php echo trans('transaction_id');?></th>
								<th><?php echo trans('payment_type');?></th>
								<th><?php echo trans('payment_load_date');?></th>
								<th><?php echo trans('amount');?></th>
								<th><?php echo trans('payment_charge');?></th>
								<th><?php echo trans('payment_details');?></th>
								<th><?php echo trans('payment_method');?></th>
								<th><?php echo trans('payment_time');?></th>
								<th><?php echo trans('payment_status');?></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$count = 1;
								$fundInfo = app('admin')->getwhere('as_payment','user_id',$this->currentUser->id);
								if(count($fundInfo)==0)
								echo "<tr><td colspan='10'>".trans('no_data_found')."</td></tr>";
								else
								foreach($fundInfo as $info){ ?>
								<tr>
									<td><?php echo $count;?></td>
									<td><?php echo ucwords($info['payment_transaction_id']);?></td>
									<td><?php echo ucwords($info['payment_type']);?></td>
									<td><?php echo getdatetime($info['payment_load_date'],3);?></td>
									<td><?php echo $info['payment_amount'];?></td>
									<td><?php echo $info['payment_charge'];?></td>
									<td><?php echo $info['payment_txn_msg'];?></td>
									<td><?php echo ucwords($info['payment_card']);?></td>
									<td><?php echo getdatetime($info['payment_time'],3);?></td>
									<td><?php 
										if($info['payment_status']=='paid'){ ?>
										
										<label class="label label-success"><?php echo ucwords($info['payment_status']); ?></label>
										
										<?php }else if($info['payment_status']=='hold'){ ?>
										
										<label class="label label-warning"><?php echo ucwords($info['payment_status']); ?></label>
										
										<?php }else{ ?>
										
										<label class="label label-danger"><?php echo ucwords($info['payment_status']); ?></label>
										
									<?php } ?></td>
								</tr>
							<?php $count++; } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h3><?php echo trans('add_fund');?>
						<span class="pull-right">
							<?php echo trans('current_balance');?>:
							<span class="current_balance_amount"><?php echo $currentFund['current_balance']; ?> Tk</span>
							
						</span>
					</h3>
					
				</div>
				<div class="ibox-content addFundForm">
					<form>
						<div class="form-group">
							<input type="text" class="form-control" name="payment_amount" placeholder="<?php echo trans("enter_your_amount");?>">
						</div>
						<div class="form-group">
							<button class="btn btn-primary" type="submit"><?php echo trans('add');?></button>
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
<?php 
	if(isset($_GET['pid'])){
?>
<div class="modal fade" id="CurrentFundShowModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header text-center">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Payment Details</h4>
						</div>
						<div class="modal-body">
							<div  class="row" style="height:50px;">
								<div class="col-sm-4 col-sm-offset-4">
								<?php if($current_fund['payment_status']=='paid'){ ?>
									<button type="button" class="btn btn-success btn-block"><?php echo ucwords($current_fund['payment_status']);?></button>
								<?php } ?>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-12">
									<p class="text-center"><b> <?php echo $current_fund['payment_txn_msg']; ?> </b></p>
								</div>
							</div>
							</br>
							<div class="row">
								<div class="col-sm-6">
									<h4><span class="fa fa-calendar text-info"></span> Payment Load Date: <?php echo $current_fund['payment_load_date'];?></h4>
								</div>
								<div class="col-sm-6">
									<h4><span class="fa fa-calendar text-info"></span> Payment Time: <?php echo $current_fund['payment_time'];?></h4>
								</div>
							</div>
							</br>
							<div class="row">
								<div class="col-sm-6">
									<h4><span class="fa fa-money text-info"></span> Payment Amount: <?php echo $current_fund['payment_amount'];?></h4>
								</div>
								<div class="col-sm-6">
									<h4><span class="fa fa-money text-info"></span> Payment Charge: <?php echo $current_fund['payment_charge'];?></h4>
								</div>
							</div>
							</br>
							<div class="row">
								<div class="col-sm-6">
									<h4><i class="fa fa-road text-info" aria-hidden="true"></i> Payment Type: <?php echo ucwords($current_fund['payment_type']);?></h4>
								</div>
								<div class="col-sm-6">
									<h4><span class="fa fa-money text-info"></span> Payment Method: <?php echo $current_fund['payment_card'];?></h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
						</div>
					</div>
				</div>
			</div>	 
			
	<?php } ?>
[footer]
<script>
$(document).ready(function(){
	<?php 
		if(isset($_GET['pid'])){
	?>
		$("#CurrentFundShowModal").modal('toggle');
	<?php 
		}
	?>
});
	$(".addFundForm form").validate({
		rules:{
			payment_amount:{
				required: true
			}
		},
		submitHandler: function(form) {
			AS.Http.PostSubmit(form, {"action" : "GetAddFund"}, "ajax/", function (response) {
				if(response.status=='success'){
					window.location = response.page;
					}else{
					swal({
						title: $_lang.error, 
						text: response.message, 
						type: "error",
						confirmButtonColor: "#1ab394", 
						confirmButtonText: $_lang.ok,
					});
				}
				
			});
		}
	});
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php'; ?> 





