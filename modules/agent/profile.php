<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';
$getuserdetails = app('admin')->getuserdetails($this->currentUser->id);
?>
[header]
	<style>
		body {
			font-family:'verdana';
		}
		
	</style>
[/header]
        <div class="wrapper">
			  <div class="row">
                <div class="col-lg-7">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5><?php echo trans('account_setting'); ?></h5>
                          
                        </div>
                         <div class="ibox-content" style="">
                            <div class="row">
                                <div class="col-sm-12" id="account_update">
                                    <form role="form">
                                        <div class="form-group">
                                           <div class="row">
                                                <div class="col-sm-6">
                                                    <label> <?php echo trans('first_name'); ?></label>
                                                    <input type="text" name="first_name" placeholder="<?php echo trans('enter_first_name'); ?>" class="form-control" value="<?php echo $getuserdetails['first_name']; ?>">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label> <?php echo trans('last_name'); ?></label>
                                                    <input type="text" name="last_name" placeholder="<?php echo trans('enter_last_name'); ?>" class="form-control" value="<?php echo $getuserdetails['last_name']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo trans('mobile'); ?></label>
                                            <input type="text" name="user_mobile" placeholder="<?php echo trans('enter_mobile_number'); ?>" class="form-control" value="<?php echo $getuserdetails['phone']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo trans('email'); ?></label>
                                            <input type="email" placeholder="<?php echo trans('enter_email'); ?>" class="form-control" value="<?php echo $getuserdetails['email']; ?>" name="user_email">
                                        </div>
                                       <!-- <div class="form-group" id="data_1">
                                            <label><?php echo trans('date_of_birth'); ?></label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" value="03/05/1996">
                                            </div>
                                        </div>-->
                                        <div class="form-group">
                                            <label><?php echo trans('address'); ?></label>
                                            <textarea rows="3" placeholder="<?php echo trans('enter_address'); ?>" class="form-control" name="user_address" ><?php echo $getuserdetails['address']; ?></textarea>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('save_changes'); ?></strong></button>
                                        </div>
                                    </form>
                                </div>
                               <!--  <div class="col-sm-6">
                                    <h4>Not a member?</h4>
                                    <p>You can create an account:</p>
                                    <p class="text-center">
                                        <a href=""><i class="fa fa-sign-in big-icon"></i></a>
                                    </p>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
					
					
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5><?php echo trans('password_setting'); ?></h5>
                           
                        </div>
                         <div class="ibox-content" style="">
                            <div class="row">
                                <div class="col-sm-12" id="change-password-form">
                                    <form role="form">
                                        
                                        <div class="form-group">
                                            <label><?php echo trans('old_password'); ?></label>
                                            <input type="password" placeholder="<?php echo trans('old_password'); ?>" class="form-control" value="" name="old_password">
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo trans('new_password'); ?></label>
                                            <input type="password" placeholder="<?php echo trans('new_password'); ?>" class="form-control" value="" name="new_password">
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo trans('confirm_password'); ?></label>
                                            <input type="password" placeholder="<?php echo trans('re_enter_your_password'); ?>" class="form-control" value="" name="new_password_confirmation">
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('save_changes'); ?></strong></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
			<div class="row">
			<?php //print_r($getuserdetails);?>
				<div class="col-sm-6">
					<div class="ibox">
						<div class="ibox-title">
							<h5>ID Card</h5>
							<button class="btn btn-success btn-sm pull-right id_card" agent_id="<?php echo $this->currentUser->id;?>"
							onclick="GetAgentIdCard(this)"><i class="fa fa-print" ></i> Print ID Card</button>
						</div>
						<div class="ibox-content">
							<div class="id-card-holder">
									<div class="id-card">
										<div class="header">
											<img src="images/logo.jpg">
										</div>
										<div class="photo">
											<img src="images/agent/01559617392.jpg">
										</div>
										<h2><?php echo $getuserdetails['first_name'].' '.$getuserdetails['last_name']; ?></h2>
										<h4><?php echo $getuserdetails['user_role']==2 ? 'Agent Manager' : 'No Designation';?></h4>
										<div class="qr-code">
											<img src="images/qr.png">
										</div>
										<h3>www.appsowl.com</h3>
										<hr>
										<p><strong>Powered By: "Software Galaxy"</strong> House-6, Road-10, Nikunja-2 <p>
										<p>Dhaka-1230, Bangladesh</p>
										<p>Ph: 01687-802090 | E-mail: softwaregalaxyltd@gmail.com</p>

									</div>
							</div>
						</div>
					</div>
			</div>
			<div class="col-sm-6">
				<div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5><?php 
							if($getuserdetails['withdrawal_password']==null){
								echo trans('add_withdrawal_password'); 
							}
							else{
								echo trans('change_withdrawal_password'); 
							}
							?></h5>
                           
                        </div>
                         <div class="ibox-content" style="">
                            <div class="row">
							 <?php 
										if($getuserdetails['withdrawal_password']!=null){
											echo $getuserdetails['withdrawal_password'];
										?>
                                <div class="col-sm-12" id="change-withdrawal-password">
                                    <form role="form">
                                        <div class="form-group">
                                            <label><?php echo trans('old_password'); ?></label>
                                            <input type="password" placeholder="<?php echo trans('old_password'); ?>" class="form-control" value="" name="old_password">
                                        </div>
										
                                        <div class="form-group">
                                            <label><?php echo trans('new_password'); ?></label>
                                            <input type="password" placeholder="<?php echo trans('new_password'); ?>" class="form-control" value="" name="new_password">
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo trans('confirm_password'); ?></label>
                                            <input type="password" placeholder="<?php echo trans('re_enter_your_password'); ?>" class="form-control" value="" name="new_password_confirmation">
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('save_changes'); ?></strong></button>
                                        </div>
                                    </form>
                                </div>
								<?php }else{?>
								 <div class="col-sm-12" id="withdrawal-password">
                                    <form role="form">
                                        <div class="form-group">
                                            <label><?php echo trans('new_password'); ?></label>
                                            <input type="password" placeholder="<?php echo trans('new_password'); ?>" class="form-control" value="" name="new_password">
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo trans('confirm_password'); ?></label>
                                            <input type="password" placeholder="<?php echo trans('re_enter_your_password'); ?>" class="form-control" value="" name="new_password_confirmation">
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('save_changes'); ?></strong></button>
                                        </div>
                                    </form>
                                </div>
								<?php }?>
                            </div>
                        </div>
                    </div>
				<!--div class="ibox">
					<div class="ibox-title">
						<h5>Visiting Card</h5>
							<button class="btn btn-success btn-sm pull-right visiting_card"><i class="fa fa-download"></i>Download Visiting Card</button>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-sm-8 col-sm-offset-2" style="border:2px solid #1ab394; min-height:200px;">
								<div class="row" >
									<div class="col-sm-6 visiting">
										<h2><?php echo $getuserdetails['first_name'].' '.$getuserdetails['last_name'];?></h2>
										<h5><?php echo $getuserdetails['user_role']==2 ? 'Agent Manager' : 'No Designation';?></h5>
										<img src="images/logo.jpg" alt="" class="img-responsive rounded">
									</div>
								
									<div class="col-sm-6 user_info">
										<p><span><i class="fa fa-phone" aria-hidden="true"></i></span> <?php echo $getuserdetails['phone'];?></p>
										<p><span><i class="fa fa-envelope" aria-hidden="true"></i></span> <?php echo $getuserdetails['email'];?></p>
										<p><span><i class="fa fa-globe" aria-hidden="true"></i></span> www.appsowl.com</p>
										<p><span><i class="fa fa-map-marker" aria-hidden="true"></i></span> <?php echo $getuserdetails['address'];?></p>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-sm-12">
										<h5 class="text-center" class=""><strong>Powered By: Software Galaxy Ltd. </strong>HotLine: +8801844519430-8<br></h5>
										<p class="text-center">Nikunja-2,Khilkhet, Dhaka, Bangladesh</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div-->
			</div>
		</div>     
<div class='last_receipt_view hidden'></div>	
</div>
		<?php include dirname(__FILE__) .'/include/footer.php'; ?>