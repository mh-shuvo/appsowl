<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <h2><?php echo trans('agent_payment'); ?></h2>
                    <div class="table-responsive">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            
                            <table class="table table-striped table-bordered table-hover dataTables-example dataTable text-center" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" role="grid">
                                <thead>
									<th class="text-center"><?php echo trans('received_id'); ?></th>
									<th class="text-center"><?php echo trans('user_name'); ?></th>
									<th class="text-center" ><?php echo trans('payment_date'); ?></th>
									<th class="text-center" ><?php echo trans('payment_type'); ?></th>
									<th class="text-center" ><?php echo trans('amount'); ?></th>
									<th class="text-center" ><?php echo trans('payment_details'); ?></th>
									<th class="text-center" ><?php echo trans('status'); ?></th>
								</thead>
								<tbody>
								<?php
								$payment=app('admin')->getwhereand('as_agent_payment','agent_id',ASSession::get("user_id"),'payment_type','receive');
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
                                           <span class="label <?php echo ($value['payment_status'] == "paid" ? "label-primary" : 'label-danger');?>">
											<?php
												echo $value['payment_status'];
											?>
                                        </td>
									</tr>
								<?php }}?>
								</tbody>
                                
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</div>
</div>
<?php include dirname(__FILE__) .'/include/footer.php'; ?>