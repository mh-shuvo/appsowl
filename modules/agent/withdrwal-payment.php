<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-9">
            <div class="ibox">
                <div class="ibox-content">
                    <h2><?php echo trans('agent_payment'); ?></h2>
                    <div class="table-responsive">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            
                            <table class="table table-striped table-bordered table-hover dataTables-example dataTable text-center" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" role="grid">
                                <thead>
									<th class="text-center"><?php echo trans('withdrawal_id'); ?></th>
									<th class="text-center"><?php echo trans('user_name'); ?></th>
									<th class="text-center" ><?php echo trans('payment_date'); ?></th>
									<th class="text-center" ><?php echo trans('payment_type'); ?></th>
									<th class="text-center" ><?php echo trans('amount'); ?></th>
									<th class="text-center" ><?php echo trans('payment_details'); ?></th>
									<th class="text-center" ><?php echo trans('status'); ?></th>
								</thead>
								<tbody>
									<?php
										$payment=app('admin')->getwhereand('as_agent_payment','agent_id',$this->currentUser->id,'payment_type','withdraw');
										if (!empty($payment)) {
											foreach ($payment as $value) {
											$userdetails = app('admin')->getwhereid('as_users','user_id',$value['user_id']); ?>
											<tr>
												<td><?php echo $value['agent_payment_id'] ?></td>
												<td><?php echo $userdetails['username']; ?></td>
												<td><?php echo getdatetime($value['payment_date'],3); ?></td>
												<td><?php echo $value['payment_method']; ?></td>
												<td><?php echo $value['payment_amount']; ?></td>
												<td><?php echo $value['payment_details']; ?></td>
												<td class="footable-visible">
													<span class="label <?php echo ($value['payment_status'] == 1 ? "label-primary" : 'label-danger');?>">
														<?php
															echo ($value['payment_status'] == 1 ? trans('paid') : trans('unpaid'));
														?>
													</td>
												</tr>
												<?php 
												}
											}
										?>
									</tbody>
									
								</table>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<div class='col-sm-3'>
				<div class='ibox'>
					<div class='ibox-title'>
						<h4><?php echo trans('withdrawal');?></h4>
					</div>
					<div class='ibox-content' id="agent_withdraw">
						<form>
							<div class='form-group'>
								<p class='h4'><?php echo trans('available_amount');?>: 
									<span style='color:forestgreen; font-weight:bold;'>
										<?php
											$agent_received = app('admin')->getsumtotalbywhereand('as_agent_payment','payment_amount','payment_type','receive','agent_id',$this->currentUser->id);
											$agent_withdrawal = app('admin')->getsumtotalbywhereand('as_agent_payment','payment_amount','payment_type','withdraw','agent_id',$this->currentUser->id);
											$agent_availiable_balance = $agent_received - $agent_withdrawal;
											echo $agent_availiable_balance;
										?>
									</span>
								</p>
							</div>
							<div class='form-group'>
								<select class='form-control' id='payment' name="method">
									<option value='cash'><?php echo trans('office_cash');?></option>
									<option value='bkash'><?php echo trans('bkash');?></option>
								</select>
							</div>
							<div class='form-group' id='account_number'>
								<input type='text' class='form-control' name='account_number' placeholder='<?php echo trans('enter_your_account_number');?>'>
							</div>
							<div class='form-group'>
								<input type='text' class='form-control' name='withdrawal_amount' placeholder='<?php echo trans('withdrawal_amount');?>'>
							</div>
							<div class='form-group'>
								<input type='password' class='form-control' name='withdrawal_password' placeholder='<?php echo trans('withdrawal_password');?>'>
							</div>
							<button type='submit' class='btn btn-success btn-sm'><?php echo trans('withdraw');?></button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	[footer]
	<script>
		$(document).ready(function(){
			$('#account_number').hide();
			$('#payment').change(function(){
				if($('#payment').val()=='bkash'){
					$('#account_number').show();
				}
				else{
					$('#account_number').hide();
				}
			})
		})
		
		$("#agent_withdraw form").validate({
			rules: {
				// withdrawal_amount: {
					// required: true,
					// number: true
				// },
				// withdrawal_password: {
					// required: true,
					// minlength: 6
				// }
			},	
			submitHandler: function(form) {
				AS.Http.submit(form, Agent_withdrawSubmit(form), false, function (result) {
					swal(result.message, "", "success");
					$('.confirm').click(function () {
						location.reload();
					});
				}); 
				
			}
		});
		
		function Agent_withdrawSubmit(form) {
			return {
				action: "Agent_withdrawSubmit",
				data: {
					method: form['method'].value,
					withdrawal_amount: form['withdrawal_amount'].value,
					account_number: form['account_number'].value,
					withdrawal_password: AS.Util.hash(form['withdrawal_password'].value),
				}
			};
		}
	</script>
	[/footer]
	
<?php include dirname(__FILE__) .'/include/footer.php'; ?>														