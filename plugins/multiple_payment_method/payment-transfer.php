<?php defined('_AZ') or die('Restricted access');

		// $getpayment=app('root')->getwhere('pos_payment_method','payment_method_status','active');
		$getpayment = app('db')->table('pos_payment_method')->where('payment_method_status','active')->get();
		$count=1;
		$total_payment_method= count($getpayment);
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
	?>
	[header]
	<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false); ?>
	<style type="text/css">
		th{text-align: center;}
	</style>
	[/header]
	<div class="row wrapper">
		<div class="col-sm-12">
			<h2><strong><?php echo trans('payment_transfer'); ?></strong></h2>
		</div>
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-title">
					<h3><?php echo trans('my_accounts'); ?></h3>
				</div>
				<div class="ibox-content">
					<table class="table">
						<thead>
							<th><?php echo trans('account_number'); ?></th>
							<th><?php echo trans('payment_method'); ?></th>
							<th><?php echo trans('added_by'); ?></th>
							<th><?php echo trans('amount'); ?></th>
							
								<th><?php echo trans('action'); ?></th>
							
						</thead>
						<tbody class="text-center">
							<?php
								foreach($getpayment as $payment){
									$user_details=app('admin')->getuserdetails($payment['user_id']);
									$getCreditAmount=app('admin')->getsumtotalbywhereandand('pos_transactions','transaction_amount','transaction_flow_type','credit','payment_method_value',$payment['payment_method_value'],'user_id',$payment['user_id']);
									$getDebitAmount=app('admin')->getsumtotalbywhereandand('pos_transactions','transaction_amount','transaction_flow_type','debit','payment_method_value',$payment['payment_method_value'],'user_id',$payment['user_id']);
								?>
								<tr>
									<td><?php echo $payment['account_number']; ?></td>
									<td><?php echo $payment['payment_method_name']; ?></td>
									<td><?php echo $user_details['first_name'].' '.$user_details['last_name']; ?></td>
									<td><?php
										
										if(($getCreditAmount-$getDebitAmount)<0){
										echo 0;}else{echo (float)($getCreditAmount-$getDebitAmount);}
									?></td>
									
										<td>
											<a href="javascript:void(0);" class="btn btn-primary btn-sm payment_transfer"
											payment_method_name="<?php echo $payment['payment_method_name']; ?>" 
											payment_method_value="<?php echo $payment['payment_method_value']; ?>"
											payment_method_amount="<?php echo $getCreditAmount-$getDebitAmount; ?>">
												
												<i class="fas fa-exchange-alt"></i> <?php echo trans('transfer'); ?>
											</a>
										</td>
									
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>		
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-title">
					<h3><?php echo trans('transaction_history'); ?></h3>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered dataTables-example transaction_history" data-title="Payment Transfer"></table>
				</div>
			</div>
		</div>
	</div>
	<div class="ModalForm">
		<div class="modal_status"></div>
	</div>	
	
	[footer]
	<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false); ?>
	<script>
		
		$(document).on("click",".confirm", function(){
			$('.show_modal').modal('hide');
			AS.Http.GetDataTable('.transaction_history',TableDataColums(),{ action : "GetTransactionHistory"},"pos/filter/",true);
		});
		
		function TableDataColums(){
			return [
			{ "title": $_lang.date,"class": "text-center", data : 'created_at' },
			{ "title": $_lang.method,"class": "text-center", data : 'payment_method_value' },
			{ "title": $_lang.amount,"class": "text-center", data : 'transaction_amount' },
			{ "title": $_lang.account_no,"class": "text-center", data : 'account_no' },
			{ "title": $_lang.note,"class": "text-center", data : 'transaction_note' }
			];
		}
		
		$(document).ready(function(){
			AS.Http.GetDataTable('.transaction_history',TableDataColums(),{ action : "GetTransactionHistory"},"pos/filter/");
		});
		
		$('.payment_transfer').click(function(){
			var payment_method_name=$(this).attr('payment_method_name');
			var payment_method_value=$(this).attr('payment_method_value');
			var payment_method_amount=$(this).attr('payment_method_amount');
			
			
			$(".show_modal").remove();
			AS.Http.posthtml({'action':'GetPaymentTransferModal','payment_method_value':payment_method_value,'payment_method_amount':payment_method_amount},'pos/modal/',function(data){
				$(".modal_status").html(data);
				$("#payment_method_name").val(payment_method_name+' ( '+payment_method_amount+' )');
				$("#payment_method_value").val(payment_method_value);
				$(".show_modal").modal('show');
			});
			
		})
		
	</script>
	[/footer]
	<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php';?>															