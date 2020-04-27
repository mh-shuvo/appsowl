<?php
	include dirname(__FILE__) .'/includes/header.php';
?>
[header]
	<?php 
		getCss('assets/system/css/plugins/datapicker/datepicker3.css');
	?>
[/header]
<section class="gray-section">
<div class="container">
	<div class="row">
		<div class="col-sm-12 agentform">
			<form>
				<div class="ibox">
					<div class="ibox-title text-center">
						<h1><strong>Agent Registration</strong></h1>
					</div>
					<div class="ibox-content">
						<div class="form-group ">
							<div class="row">
								<div class="col-sm-3 text-right">
									<label class="control-label">First Name:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="first_name" placeholder="Enter your First Name">
									<small>Ex: Mehedi</small>
								</div>
							</div>
						</div>
						<div class="form-group ">
							<div class="row">
								<div class="col-sm-3 text-right">
									<label class="control-label">Last Name:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="last_name" placeholder="Enter your Last Name">
									<small>Ex: Hasan</small>
								</div>
							</div>
						</div>
						<div class="form-group ">
							
							<div class="row">
							<div class="col-sm-3 text-right">
									<label class="control-label">Mobile Number:</label>
								</div>
                                        <div class="col-md-2">
                                            <div class="row-fluid">
                                                <select class="form-control" name="country_code" id="country_code" onchange="getcountrycode();" data-show-subtext="true" data-live-search="true">
                                                    <option value="" selected>Select Country</option>
                                                    <?php $getcountry = app('admin')->getall('as_country'); 
													foreach($getcountry as $getcountry){ ?>
                                                        <option value="+<?php echo $getcountry['phonecode']; ?>">
                                                            <?php echo $getcountry['name']; ?>
                                                        </option>
                                                        <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <input type="text" name="agent_phone" id="agent_phone" class="form-control" placeholder="Mobile Number" >
                                            </div>
                                        </div>
                                    </div>
						</div>
						<div class="form-group ">
							<div class="row">
								<div class="col-sm-3 text-right">
									<label class="control-label">Email:</label>
								</div>
								<div class="col-sm-9">
									<input type="email" class="form-control" name="agent_email" placeholder="Enter your Valid Email Address">
									<small>Ex: test@mail.com</small>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3 text-right">
									<label class="control-label">Birthday:</label>
								</div>
								<div class="col-sm-9" id="data_1">
									<input type="text" class="form-control date" name="agent_birthday" placeholder="Enter your Birthday">
									<small>Ex: 12 January 1985</small>
								</div>
							</div>
						</div>
						<div class="form-group ">
							<div class="row">
								<div class="col-sm-3 text-right">
									<label class="control-label">Present Address:</label>
								</div>
								<div class="col-sm-9">
									<textarea class="form-control" name="agent_present_address"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group ">
							<div class="row">
								<div class="col-sm-3 text-right">
									<label class="control-label">Permanent Address:</label>
								</div>
								<div class="col-sm-9">
									<textarea class="form-control" name="agent_permanent_address"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group ">
							<div class="row">
								<div class="col-sm-3 text-right">
									<label class="control-label">Zone/Thana:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="agent_zone">
									<small>Ex: Uttara</small>
								</div>
							</div>
						</div>
						<div class="form-group ">
							<div class="row">
								<div class="col-sm-3 text-right">
									<label class="control-label">Area:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="agent_area">
									<small>Ex: Uttara1, Uttara-2, Jihl Par</small>
								</div>
							</div>
						</div>
						<div class="form-group ">
							<div class="row">
								<div class="col-sm-3 text-right">
									<label class="control-label">Agent Image:</label>
								</div>
								<div class="col-sm-9">
									<input type="file" class="form-control-file" name="agent_image">
									<small>Newly painted colored photos</small>
								</div>
							</div>
						</div>
						<!--div class="form-group ">
							<div class="row">
								<div class="col-sm-3 text-right">
									<label class="control-label">Attach Document:</label>
								</div>
								<div class="col-sm-9">
									<input type="file" class="form-control-file" name="agent_attach">
									
								</div>
							</div>
						</div-->
						<div class="form-group ">
							<div class="row">
								<div class="col-sm-3 text-right">
									<label class="control-label">National ID Card:</label>
								</div>
								<div class="col-sm-9">
									<input type="file" class="form-control-file" name="agent_nid">
									<small>File format should be .pdf,.jpg,jpeg </small>
								</div>
							</div>
						</div>
						<div class="form-group ">
							<div class="row">
								<div class="col-sm-3 text-right">
									<label class="control-label">Password</label>
								</div>
								<div class="col-sm-9">
									<input type="password" class="form-control" name="agent_password">
								</div>
							</div>
						</div>
						<div class="form-group ">
							<div class="row">
								<div class="col-sm-3 text-right">
									<label class="control-label">Confirm Password:</label>
								</div>
								<div class="col-sm-9">
									<input type="password" class="form-control" name="password_confirmation">
								</div>
							</div>
						</div>
						<div class="form-group ">
							<div class="row">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-success pull-right">Submit</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
	
</section>
[footer]
<?php getJs('assets/system/js/plugins/datapicker/bootstrap-datepicker.js'); ?>
<script>

$(document).ready(function(){
	$('#data_1 .date').datepicker({
		todayBtn: "linked",
		format: "yyyy-mm-dd",
		keyboardNavigation: false,
		forceParse: false,
		calendarWeeks: true,
		autoclose: true
	});
});
</script>
[/footer]
<?php include dirname(__FILE__) .'/includes/footer.php'; ?>