<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';
if (!empty($this->route['id'])) {
    $sub_history=app('admin')->getwhere('as_agent_payment','subscribe_id',$this->route['id']);
} else {
    $sub_history=app('admin')->getwhere('as_agent_payment','agent_id',ASSession::get("user_id"));
}
?>

<div class="wrapper wrapper-content animated fadeInRight">
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h3><?php echo trans('subscription_history'); ?></h3>
            </div>
                  <div class="ibox-content">
                
                    <div class="table-responsive">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        
                            <table class="table table-striped table-bordered table-hover dataTables-example dataTable text-center" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" role="grid">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 250px;"><?php echo trans('agent_name'); ?></th>
                                        <th class="text-center sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 289px;"><?php echo trans('user_name'); ?></th>
                                        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 356px;"><?php echo trans('payment_type'); ?></th>
                                        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 356px;"><?php echo trans('payment_date'); ?></th>
                                        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 356px;"><?php echo trans('payment_method'); ?></th>
                                        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 322px;"><?php echo trans('payment_amount'); ?></th>
                                        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 322px;"><?php echo trans('payment_charge'); ?></th>
                                        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 322px;"><?php echo trans('payment_details'); ?></th>
                                        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 322px;"><?php echo trans('payment_status'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
									<?php 
										
									foreach($sub_history as $history){ 
										$userdetails = app('admin')->getwhereid('as_user_details','user_id',$history['user_id']);
										$agentdetails = app('admin')->getwhereid('as_user_details','user_id',$history['agent_id']);
										?>
                                    <tr class="gradeA odd" role="row">
                                            <td class="footable-visible footable-first-column"><span class="footable-toggle"></span> <?php echo $agentdetails['first_name'].' '.$agentdetails['last_name']; ?>
                                            </td>
											 <td class="footable-visible footable-first-column"><span class="footable-toggle"></span> <?php echo $userdetails['first_name'].' '.$userdetails['last_name'] ; ?></td>
                                            <td class="footable-visible">
                                               <?php echo $history['payment_type']; ?>
                                            </td>
                                            <td class="footable-visible">
                                               <?php echo getdatetime($history['payment_date'],3); ?>
                                            </td>
											<td class="footable-visible">
                                               <?php echo $history['payment_method']; ?>
                                            </td>
                                            <td class="footable-visible">
                                               <?php echo $history['payment_amount']; ?>
                                            </td>
                                             <td class="footable-visible">
                                               <?php echo $history['payment_charge']; ?>
                                            </td>
											<td class="footable-visible">
                                               <?php echo $history['payment_details']; ?>
                                            </td>
                                            <td class="footable-visible text-uppercase">
                                               <span class="label <?php echo ($history['payment_status'] == 'paid' ? "label-primary" : 'label-danger');?>">
												<?php
													echo $history['payment_status'];
												?>
												</span>
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
</div>
     <?php include dirname(__FILE__) .'/include/footer.php'; ?> 





