<?php defined('_AZ') or die('Restricted access'); 
	$key = md5(date('ymdhi'));
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	$getSubscriptions = app('admin')->getwhere('as_subscribe','user_id',ASSession::get("user_id"));
	$getPaymentHistory = app('admin')->getallLimitAnd('as_payment','user_id','10','payment_id','payment_status','paid',ASSession::get("user_id"));
	$getSoftware = app('admin')->getall('as_software');
?>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <!-- App Pricing -->
                <div class="ibox">
                    <div class="ibox-content landing-page">
                        <div class="container">
                            <div class="row m-b-lg">
                                <div class="col-lg-12 text-center">
                                    <div class="navy-line"></div>
                                    <h1><?php echo trans('software_registration'); ?></h1>

                                </div>
                            </div>
                            <div class="row">
                                <?php foreach($getSoftware as $software) { ?>
                                    <div class="col-lg-4 wow zoomIn animated" style="visibility: visible;">
                                        <ul class="pricing-plan list-unstyled selected">
                                            <li class="pricing-title">
                                                <?php echo trans('point_of_sale'); ?>
                                            </li>
                                            <li class="pricing-price">
                                                <span>Starting From <?php echo $software['software_price']." ".'TAKA'; ?>/</span> month
                                            </li>
                                            <?php echo $software['software_short_des']; ?>
                                                <li id="ad_button2">
                                                    <span class="label label-primary" style="cursor: pointer;" onclick="show2()"><?php echo trans('advance_view_detail'); ?></span>
                                                </li>

                                                <div style="display: none;" id="test2">
                                                    <?php echo $software['software_long_des']; ?>
                                                        <li>
                                                            <span class="label label-danger" style="cursor: pointer;" onclick="hide2()"><?php echo trans('small_detail'); ?></span>
                                                        </li>
                                                </div>
                                                <?php 
											$result = app('root')->select("SELECT * FROM `as_subscribe` WHERE `software_id` = :sid AND `subscribe_type` = 'software' AND `user_id` = :id", array( 'id' => $this->currentUser->id, 'sid' => $software['software_id']));
											$subusercheck = count($result) > 0 ? $result[0] : null;
											if ($subusercheck) { 
											if ($subusercheck['subscribe_status'] == 'expire') { 
											?>
                                                    <li class="plan-action">
                                                        <a class="btn btn-primary" href="history">
                                                            <?php echo trans('renew_now'); ?>
                                                        </a>
                                                    </li>
                                                    <?php 
											}elseif ($subusercheck['subscribe_status'] == 'active') { 
											$getsubdomain = app('admin')->getwhereid('as_sub_domain','user_id',$this->currentUser->id);
											?>
                                                        <li class="plan-action">
                                                            <a class="btn btn-success" href="<?php echo '//'.$getsubdomain['sub_domain'].'.'.$getsubdomain['root_domain'].'/pos/' ?>">
                                                                <?php echo trans('go_to_dashboard'); ?>
                                                            </a>
                                                        </li>
                                                        <?php  
											}elseif($subusercheck['subscribe_status'] == "inactive"){ ?>
                                                            <li class="plan-action">
                                                                <a class="btn btn-warning" href="javascript:void(0);">
                                                                    <?php echo trans('inactive'); ?>
                                                                </a>
                                                            </li>
                                                            <?php
											}elseif ($subusercheck['subscribe_status'] == 'cancel') { ?>
                                                                <li class="plan-action">
                                                                    <a class="btn btn-danger" href="javascript:void(0);">
                                                                        <?php echo trans('cancel'); ?>
                                                                    </a>
                                                                </li>
                                                                <?php
											}

											}else{ ?>
                                                                    <li class="plan-action">
                                                                        <a class="btn btn-warning" href="/subscribe-pos-setting">
                                                                            <?php echo trans('install_now'); ?>
                                                                        </a>
                                                                    </li>
                                                                    <?php } ?>
                                        </ul>
                                    </div>
                                    <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <div class="ibox">
                    <div class="ibox-title">
                        <h3><?php echo trans('software_subscriptions'); ?></h3>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-center dataTables-example" id="">
							<thead>
								<tr role="row">
									<th class="text-center"><?php echo trans('subscribe_id'); ?></th>
									<th class="text-center"><?php echo trans('subscribe_date'); ?></th>
									<th class="text-center"><?php echo trans('title'); ?></th>  
									<th class="text-center"><?php echo trans('type'); ?></th>
									<th class="text-center"><?php echo trans('status'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$count=1;
									$subscribe_details = app('admin')->getwhere('as_subscribe','user_id',ASSession::get("user_id"));
									foreach($subscribe_details as $subscribe){
										$software = app('admin')->getwhereid('as_software','software_id',$subscribe['software_id']);
										$checkBillingType= $software['software_billing_type'];
										$invoice_data = app('root')->select("SELECT `invoice_id`,`subscribe_id`,`invoice_status`,`subscribe_start_date`,`subscribe_end_date`,`invoice_amount` FROM `as_invoices` WHERE `subscribe_id` = :id ORDER BY `invoice_id` DESC LIMIT 1", 
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
											<?php echo $title; ?>
										</td>
										<td>
											<?php echo ucwords($subscribe['subscribe_type']);?>
										</td>
										<td>
											<span class="label <?php echo ($subscribe['subscribe_status'] == 'active' ? 'label-primary' : 'label-danger');?>">
												<?php
													echo strtoupper($subscribe['subscribe_status']);
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
            <div class="col-sm-7">
                <div class="ibox">
                    <div class="ibox-title">
                        <h3><?php echo trans('last_ten_payment_history'); ?></h3>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-center">
                                <thead>
                                    <th class="text-center">
                                        <?php echo trans('serial_number'); ?>
                                    </th>
                                    <th class="text-center">
                                        <?php echo trans('payment_date'); ?>
                                    </th>
                                    <th class="text-center">
                                        <?php echo trans('payment_type'); ?>
                                    </th>
                                    <th class="text-center">
                                        <?php echo trans('amount'); ?>
                                    </th>
                                    <th class="text-center">
                                        <?php echo trans('status'); ?>
                                    </th>
                                </thead>
                                <tbody>
                                    <?php foreach($getPaymentHistory as $history){ ?>
                                        <tr>
                                            <td>
                                                <?php echo $history['payment_id'];?>
                                            </td>
                                            <td>
                                                <?php echo getdatetime($history['payment_time'],3);?>
                                            </td>
                                            <td>
                                                <?php echo $history['payment_card'];?>
                                            </td>
                                            <td>
                                                <?php echo $history['payment_amount'];?>
                                            </td>
                                            <td><span class="label <?php echo ($history['payment_status'] == 'active' ? " label-primary " : 'label-danger');?>">
											<?php echo $history['payment_status']; ?></span></td>
                                        </tr>
                                        <?php }?>
                                </tbody>
                            </table>
                         </div>
                    </div>
                </div>
            </div>
        </div>
        [footer]
        <script type="text/javascript">
            function show() {
                var adv = document.getElementById('ad_button');
                var test = document.getElementById("test");

                adv.style.display = "none";
                test.style.display = 'block';
            }
            function hide() {
                var adv = document.getElementById('ad_button');
                var test = document.getElementById("test");

                adv.style.display = "block";
                test.style.display = 'none';
            }
            function show2() {
                var adv2 = document.getElementById('ad_button2');
                var test2 = document.getElementById("test2");

                adv2.style.display = "none";
                test2.style.display = 'block';

            }
            function hide2() {
                var adv2 = document.getElementById('ad_button2');
                var test2 = document.getElementById("test2");
                adv2.style.display = "block";
                test2.style.display = 'none';
            }
            function show3() {
                var adv3 = document.getElementById('ad_button3');
                var test3 = document.getElementById("test3");
                adv3.style.display = "none";
                test3.style.display = 'block';
            }
            function hide3() {
                var adv3 = document.getElementById('ad_button3');
                var test3 = document.getElementById("test3");
                adv3.style.display = "block";
                test3.style.display = 'none';
            }
        </script>
        [/footer]
        <?php include dirname(__FILE__) .'/include/footer.php'; ?>	