<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row" id="manage_user">
		<form>
			<div class="col-lg-7">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5><?php echo trans('user_registration'); ?></h5>
					</div>
					<div class="ibox-content" style="">
						<div class="row">
							<div class="col-sm-12 b-r">
								<div class="form-group">
									<div class="row">
										<div class="col-sm-6">
											<label> <?php echo trans('first_name'); ?></label>
											<input type="text" name="first_name" placeholder="<?php echo trans('enter_name'); ?>" class="form-control" >
										</div>
										<div class="col-sm-6">
											<label> <?php echo trans('last_name'); ?></label>
											<input type="text" name="last_name" placeholder="<?php echo trans('enter_name'); ?>" class="form-control" >
										</div>
									</div>
								</div>
								<div class="form-group">
									<label><?php echo trans('mobile'); ?></label>
									<input type="text" name="user_mobile" placeholder="<?php echo trans('enter_mobile_number'); ?>" class="form-control" value="<?php echo $this->currentUser->country_code; ?>">
								</div>
								<div class="form-group">
									<label><?php echo trans('email'); ?></label>
									<input type="email" placeholder="<?php echo trans('enter_email'); ?>" class="form-control" name="user_email">
								</div>
								<div class="form-group">
									<label><?php echo trans('address'); ?></label>
									<textarea rows="3" placeholder="<?php echo trans('enter_address'); ?>" class="form-control" name="user_address" ></textarea>
								</div>
								<div class="form-group">
									<label><?php echo trans('password'); ?></label>
									<input type="password" placeholder="<?php echo trans('enter_password'); ?>" class="form-control" name="user_password">
								</div>
								<div class="form-group">
									<label><?php echo trans('confirm_password'); ?></label>
									<input type="password" placeholder="<?php echo trans('re_enter_your_password'); ?>" class="form-control" name="confirm_password">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-5">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5><?php echo trans('software_access_permission_setting'); ?></h5>
						
					</div>
					<div class="ibox-content">
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="headingOne">
									<h4 class="panel-title">
										<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
											<?php echo trans('point_of_sale'); ?>
										</a>
									</h4>
								</div>
								<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
									<div class="panel-body">
										<div class="switch row">
											<div class="col-md-6">
												<label><?php echo trans('sale'); ?></label>
											</div>
											<div class="col-md-6 ">
												<input type="radio" name="pos_sale"  value="edit"> Edit
												<input type="radio" name="pos_sale" value="view"> View
												<input type="radio" name="pos_sale" value="off" checked> OFF
										
											</div>
										</div>
									
										
										<br>
										<div class="switch row">
											<div class="col-md-6">
												<label><?php echo trans('category'); ?></label>
											</div>
											<div class="col-md-6">
												<input type="radio" name="pos_category" value="edit"> Edit
												<input type="radio" name="pos_category" value="view"> View
												<input type="radio" name="pos_category" value="off" checked> OFF
											</div>
										</div> <br>
										<div class="switch row">
											<div class="col-md-6">
												<label><?php echo trans('unit'); ?></label>
											</div>
											<div class="col-md-6">
												<input type="radio" name="pos_unit" value="edit"> Edit
												<input type="radio" name="pos_unit" value="view"> View
												<input type="radio" name="pos_unit" value="off" checked> OFF
											</div>
										</div> <br>
										<div class="switch row">
											<div class="col-md-6">
												<label><?php echo trans('product'); ?></label>
											</div>
											<div class="col-md-6">
												<input type="radio" name="pos_product" value="edit"> Edit
												<input type="radio" name="pos_product" value="view"> View
												<input type="radio" name="pos_product" value="off" checked> OFF
											</div>
										</div> <br>
										<div class="switch row">
											<div class="col-md-6">
												<label><?php echo trans('supplier'); ?></label>
											</div>
											<div class="col-md-6">
												<input type="radio" name="pos_supplier" value="edit"> Edit
												<input type="radio" name="pos_supplier" value="view"> View
												<input type="radio" name="pos_supplier" value="off" checked> OFF
											</div>
										</div> <br>
										<div class="switch row">
											<div class="col-md-6">
												<label><?php echo trans('stock'); ?></label>
											</div>
											<div class="col-md-6">
												<input type="radio" name="pos_stock" value="edit"> Edit
												<input type="radio" name="pos_stock" value="view"> View
												<input type="radio" name="pos_stock" value="off" checked> OFF
											</div>
										</div> <br>
										<div class="switch row">
											<div class="col-md-6">
												<label><?php echo trans('customer'); ?></label>
											</div>
											<div class="col-md-6">
												<input type="radio" name="pos_customer" value="edit"> Edit
												<input type="radio" name="pos_customer" value="view"> View
												<input type="radio" name="pos_customer" value="off" checked> OFF
											</div>
										</div> <br>
										<div class="switch row">
											<div class="col-md-6">
												<label><?php echo trans('return'); ?></label>
											</div>
											<div class="col-md-6">
												<input type="radio" name="pos_return" value="edit"> Edit
												<input type="radio" name="pos_return" value="view"> View
												<input type="radio" name="pos_return" value="off" checked> OFF
											</div>
										</div>
										<br>
										<div class="switch row">
											<div class="col-md-6">
												<label><?php echo trans('damage'); ?></label>
											</div>
											<div class="col-md-6">
												<input type="radio" name="pos_damage" value="edit"> Edit
												<input type="radio" name="pos_damage" value="view"> View
												<input type="radio" name="pos_damage" value="off" checked> OFF
											</div>
										</div> <br>
										<div class="switch row">
											<div class="col-md-6">
												<label><?php echo trans('report'); ?></label>
											</div>
											<div class="col-md-6">
												<input type="radio" name="pos_report" value="edit"> Edit
												<input type="radio" name="pos_report" value="view"> View
												<input type="radio" name="pos_report" value="off" checked> OFF
											</div>
										</div> <br>
									</div>
								</div>
							</div>
						
						</div>
					</div>
				</div>
				<div>
                    <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit">
                      <strong>
                            <?php echo trans('save_changes'); ?>
                      </strong>
                    </button>
				</div>
			</div>
		</form>
	</div>
	
</div>
<?php
$page_footer='
<script>


</script>

';

include dirname(__FILE__) .'/include/footer.php'; ?>





