<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row" id="create_user">
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
											<input type="text" name="reg_first_name" placeholder="<?php echo trans('enter_name'); ?>" class="form-control" >
										</div>
										<div class="col-sm-6">
											<label> <?php echo trans('last_name'); ?></label>
											<input type="text" name="reg_last_name" placeholder="<?php echo trans('enter_name'); ?>" class="form-control" >
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<div class="row">
											<select class="form-control" name="country_code" id="country_code" onchange="getcountrycode();" data-show-subtext="true" data-live-search="true">
												<option value="" selected><?php echo trans('select_country'); ?></option>
												<?php $getcountry = app('admin')->getall('as_country'); 
													foreach($getcountry as $getcountry){ ?>
													<option value="+<?php echo $getcountry['phonecode']; ?>"><?php echo $getcountry['name']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-md-8">
										<div class="form-group">
											<input type="text" name="reg_phone" id="reg_phone" class="form-control" placeholder="<?php echo trans('enter_mobile_number'); ?>" required="" >
										</div>
									</div>
								</div>
								<div class="form-group">
									<label><?php echo trans('email'); ?></label>
									<input type="email" name="reg_email" placeholder="<?php echo trans('enter_email'); ?>" class="form-control" name="user_email">
								</div>
								<label>
									<label style=""><?php echo trans('birthday');?></label>
								</label>
								<div class="row">
									<div class="col-xs-4">
										<div class="form-group">
											<select type="number" name="reg_birthday" class="form-control" data-show-subtext="true" data-live-search="true">
												<option><?php echo trans('day'); ?></option>
												<?php  
													for ($x = 1; $x <= 31; $x++) {
														echo "<option value='$x'>$x</option>";
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-4">
										<div class="form-group">
											<select type="text" name="reg_birthmonth" class="form-control" data-show-subtext="true" data-live-search="true">
												<option><?php echo trans('month'); ?></option>
												<option value="1">January</option>
												<option value="2">February</option>
												<option value="3">March</option>
												<option value="4">April</option>
												<option value="5">May</option>
												<option value="6">June</option>
												<option value="7">July</option>
												<option value="8">August</option>
												<option value="9">September</option>
												<option value="10">October</option>
												<option value="11">November</option>
												<option value="12">December</option>
											</select>
										</div>
									</div>
									<div class="col-xs-4">
										<div class="form-group">
											<select type="number" name="reg_birthyear" class="form-control" data-show-subtext="true" data-live-search="true">
												<option><?php echo trans('years'); ?></option>
												<?php  
													for ($x = 1900; $x <= date('Y'); $x++) {
														echo "<option value='$x'>$x</option>";
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label><?php echo trans('password'); ?></label>
									<input type="password" name="password" placeholder="<?php echo trans('enter_password'); ?>" class="form-control" name="user_password">
								</div>
								<div class="form-group">
									<label><?php echo trans('confirm_password'); ?></label>
									<input type="password" name="password_confirmation" placeholder="<?php echo trans('re_enter_your_password'); ?>" class="form-control" name="confirm_password">
								</div>
								<div class="form-group">
									<label>
										<label><?php echo trans('gender'); ?></label>
									</label>
									<br>
									<label class="radio-inline">
										<div class="form-group">
											<input type="radio" name="reg_gender"  class="radio-primary" value="Male">
											<p style="font-size:16px;"><b><?php echo trans('male'); ?></b></p>
										</div>
									</label>
									<label class="radio-inline">
										<div class="form-group">
											<input type="radio" name="reg_gender"  class="radio-primary" value="Female">
											<p style="font-size:16px;"><b><?php echo trans('female'); ?></b></p>
										</div>
									</label>
								</div>
								<div class='form-group'>
									<button type='submit' class='btn btn-primary btn-md pull-right'><?php echo trans('submit'); ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	
</div>
[footer]
<script>

	</script>
	[/footer]
	<?php
	
include dirname(__FILE__) .'/include/footer.php'; ?>





