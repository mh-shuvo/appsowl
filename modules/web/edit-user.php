<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
$getuserdetails = app('admin')->getuserdetails($this->route['id']);
?>

<div class="wrapper wrapper-content animated fadeInRight">
	  <div class="row">
        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?php echo "Update User Account Setting"; ?></h5>
 
                </div>
                 <div class="ibox-content" style="">
                    <div class="row">
                        <div class="col-sm-12" id="manage_update_user">
                            <form role="form">
							<input type="hidden" name="user_id" value="<?php echo $_GET['id']; ?>">
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
                                <div class="form-group">
                                    <label><?php echo trans('address'); ?></label>
                                    <textarea rows="3" placeholder="<?php echo trans('enter_address'); ?>" class="form-control" name="user_address" ><?php echo $getuserdetails['address']; ?></textarea>
                                </div>
								<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
									<div class="panel panel-default">
										<div class="panel-heading" role="tab" id="headingOne">
											<h4 class="panel-title">
												<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
													<?php echo trans('point_of_sale'); ?>
													<span class='pull-right fa fa-plus'></span>
												</a>
											</h4>
										</div>
										<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
											<div class="panel-body">
												<div class="switch row">
													<div class="col-md-6">
														<label><?php echo trans('sale'); ?></label>
													</div>
													<div class="col-md-6">
														<input type="radio" <?php echo $getuserdetails['pos_sale'] == 'edit' ? 'checked' : '';?> name="pos_sale" value="edit"> Edit
														<input type="radio" <?php echo $getuserdetails['pos_sale'] == 'view' ? 'checked' : '';?> name="pos_sale" value="view"> View
														<input type="radio"<?php echo $getuserdetails['pos_sale'] == 'off' ? 'checked' : '';?> name="pos_sale" value="off"> OFF
													</div>
												</div>
												<br>
												<div class="switch row">
													<div class="col-md-6">
														<label><?php echo trans('category'); ?></label>
													</div>
													<div class="col-md-6">
														<input type="radio" <?php echo $getuserdetails['pos_category'] == 'edit' ? 'checked' : '';?> name="pos_category" value="edit"> Edit
														<input type="radio" <?php echo $getuserdetails['pos_category'] == 'view' ? 'checked' : '';?> name="pos_category" value="view"> View
														<input type="radio"<?php echo $getuserdetails['pos_category'] == 'off' ? 'checked' : '';?> name="pos_category" value="off"> OFF
													</div>
												</div> <br>
												<div class="switch row">
													<div class="col-md-6">
														<label><?php echo trans('unit'); ?></label>
													</div>
													<div class="col-md-6">
														<input type="radio" <?php echo $getuserdetails['pos_unit'] == 'edit' ? 'checked' : '';?> name="pos_unit" value="edit"> Edit
														<input type="radio" <?php echo $getuserdetails['pos_unit'] == 'view' ? 'checked' : '';?> name="pos_unit" value="view"> View
														<input type="radio"<?php echo $getuserdetails['pos_unit'] == 'off' ? 'checked' : '';?> name="pos_unit" value="off"> OFF
													</div>
												</div> <br>
												<div class="switch row">
													<div class="col-md-6">
														<label><?php echo trans('product'); ?></label>
													</div>
													<div class="col-md-6">
														<input type="radio" <?php echo $getuserdetails['pos_product'] == 'edit' ? 'checked' : '';?> name="pos_product" value="edit"> Edit
														<input type="radio" <?php echo $getuserdetails['pos_product'] == 'view' ? 'checked' : '';?> name="pos_product" value="view"> View
														<input type="radio"<?php echo $getuserdetails['pos_product'] == 'off' ? 'checked' : '';?> name="pos_product" value="off"> OFF
													</div>
												</div> <br>
												<div class="switch row">
													<div class="col-md-6">
														<label><?php echo trans('supplier'); ?></label>
													</div>
													<div class="col-md-6">
														<input type="radio" <?php echo $getuserdetails['pos_supplier'] == 'edit' ? 'checked' : '';?> name="pos_supplier" value="edit"> Edit
														<input type="radio" <?php echo $getuserdetails['pos_supplier'] == 'view' ? 'checked' : '';?> name="pos_supplier" value="view"> View
														<input type="radio"<?php echo $getuserdetails['pos_supplier'] == 'off' ? 'checked' : '';?> name="pos_supplier" value="off"> OFF
													</div>
												</div> <br>
												<div class="switch row">
													<div class="col-md-6">
														<label><?php echo trans('stock'); ?></label>
													</div>
													<div class="col-md-6">
														<input type="radio" <?php echo $getuserdetails['pos_stock'] == 'edit' ? 'checked' : '';?> name="pos_stock" value="edit"> Edit
														<input type="radio" <?php echo $getuserdetails['pos_stock'] == 'view' ? 'checked' : '';?> name="pos_stock" value="view"> View
														<input type="radio"<?php echo $getuserdetails['pos_stock'] == 'off' ? 'checked' : '';?> name="pos_stock" value="off"> OFF
													</div>
												</div> <br>
												<div class="switch row">
													<div class="col-md-6">
														<label><?php echo trans('customer'); ?></label>
													</div>
													<div class="col-md-6">
														<input type="radio" <?php echo $getuserdetails['pos_customer'] == 'edit' ? 'checked' : '';?> name="pos_customer" value="edit"> Edit
														<input type="radio" <?php echo $getuserdetails['pos_customer'] == 'view' ? 'checked' : '';?> name="pos_customer" value="view"> View
														<input type="radio"<?php echo $getuserdetails['pos_customer'] == 'off' ? 'checked' : '';?> name="pos_customer" value="off"> OFF
													</div>
												</div> <br>
												<div class="switch row">
													<div class="col-md-6">
														<label><?php echo trans('return'); ?></label>
													</div>
													<div class="col-md-6">
														<input type="radio" <?php echo $getuserdetails['pos_return'] == 'edit' ? 'checked' : '';?> name="pos_return" value="edit"> Edit
														<input type="radio" <?php echo $getuserdetails['pos_return'] == 'view' ? 'checked' : '';?> name="pos_return" value="view"> View
														<input type="radio"<?php echo $getuserdetails['pos_return'] == 'off' ? 'checked' : '';?> name="pos_return" value="off"> OFF
													</div>
												</div>
												<br>
												<div class="switch row">
													<div class="col-md-6">
														<label><?php echo trans('damage'); ?></label>
													</div>
													<div class="col-md-6">
														<input type="radio" <?php echo $getuserdetails['pos_damage'] == 'edit' ? 'checked' : '';?> name="pos_damage" value="edit"> Edit
														<input type="radio" <?php echo $getuserdetails['pos_damage'] == 'view' ? 'checked' : '';?> name="pos_damage" value="view"> View
														<input type="radio"<?php echo $getuserdetails['pos_damage'] == 'off' ? 'checked' : '';?> name="pos_damage" value="off"> OFF
													</div>
												</div> <br>
												<div class="switch row">
													<div class="col-md-6">
														<label><?php echo trans('report'); ?></label>
													</div>
													<div class="col-md-6">
														<input type="radio" <?php echo $getuserdetails['pos_report'] == 'edit' ? 'checked' : '';?> name="pos_report" value="edit"> Edit
														<input type="radio" <?php echo $getuserdetails['pos_report'] == 'view' ? 'checked' : '';?> name="pos_report" value="view"> View
														<input type="radio"<?php echo $getuserdetails['pos_report'] == 'off' ? 'checked' : '';?> name="pos_report" value="off"> OFF
													</div>
												</div> <br>
											</div>
										</div>
									</div>
								
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
                        <div class="col-sm-12" id="user-change-password-form">
                            <form role="form">
                                <input type="hidden" name="user_id" value="<?php echo $_GET['id1']; ?>">
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
    </div>
</div>
<?php include dirname(__FILE__) .'/include/footer.php'; ?>