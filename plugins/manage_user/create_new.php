<?php defined('_AZ') or die('Restricted access');
	app('admin')->checkAddon('manage_user',true);		
	app('pos')->checkPermission('manage_user','edit',true) or die(redirect("/pos/access-denied"));
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
		if(isset($this->route['id'])){
			$user_info = app('admin')->getuserdetails($this->route['id']);
			$user_id = $user_info['user_id'];
		}
	?>
	[header]
	<?php 
		getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
		getCss('assets/system/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css');
	?>
	[/header]
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row manage_user">
			<form>
				<?php if(isset($this->route['id']) && $user_info){ ?>
					<input type="hidden" name="user_id" value="<?php echo $user_info['user_id']; ?>" >
				<?php } ?>
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
												<input type="text" name="first_name" placeholder="<?php echo trans('enter_name'); ?>" class="form-control" value="<?php if(isset($this->route['id'])) echo $user_info['first_name']; ?>" >
											</div>
											<div class="col-sm-6">
												<label> <?php echo trans('last_name'); ?></label>
												<input type="text" name="last_name" placeholder="<?php echo trans('enter_name'); ?>" class="form-control" value="<?php if(isset($this->route['id'])) echo $user_info['last_name']; ?>">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6">
												<label><?php echo trans('country'); ?></label>
												<select class="form-control" name="country_code" id="country_code" onchange="getcountrycode();" data-show-subtext="true" data-live-search="true">
													<option value="" selected>Country</option>
													<?php $getcountry = app('root')->select('SELECT * FROM `as_country`'); 
														foreach($getcountry as $getcountry){ ?>
														<option value="+<?php echo $getcountry['phonecode']; ?>" <?php if(isset($this->route['id'])&&$getcountry['phonecode']==str_replace('+',"", $user_info['country_code'])){echo "selected";} ?> >
															<?php echo $getcountry['name']; ?>
														</option>
													<?php } ?>
												</select>
											</div>
											<div class="col-sm-6">
												<label><?php echo trans('mobile'); ?></label>
												<input type="text" name="user_mobile" id="user_mobile" placeholder="<?php echo trans('enter_mobile_number'); ?>" class="form-control" value="<?php if(isset($this->route['id'])) echo $user_info['phone']; ?>">
											</div>
											<!--div class="col-sm-3">
												
											</div-->
										</div>
									</div>
									<?php
										if(app('admin')->checkAddon('multiple_store_warehouse')){
									?>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label">
														<?php echo trans('business_location'); ?>
													</label>
													<select class="form-control" id="store_id" name="store_id">
														<option value=""><?php echo trans('store'); ?></option>
														<?php $posStores = app('admin')->getall('pos_store'); foreach($posStores as $posStore){	?>
															<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore['store_id'] == $this->currentUser->store_id) echo "selected"; ?>><?php echo $posStore['store_name']; ?></option>
														<?php } ?>
													</select>
												</div>	
											</div>
										<div class="col-sm-6">
												<label><?php echo trans('email'); ?></label>
												<input type="email" placeholder="<?php echo trans('enter_email'); ?>" class="form-control" name="user_email"  value="<?php if(isset($this->route['id'])) echo $user_info['email']; ?>">
											</div>
										</div>
										
									</div>
										<?php } ?>
									<div class="form-group">
										<label><?php echo trans('address'); ?></label>
										<textarea rows="3" placeholder="<?php echo trans('enter_address'); ?>" class="form-control" name="user_address"><?php if(isset($this->route['id'])) echo $user_info['address']; ?></textarea>
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
						<?php 
						
							$permissions = app('db')->select("SELECT * FROM `pos_user_permission`");
							
							$count = 1;
							foreach($permissions as $permission){
						?>
							<div class="switch row">
								<div class="col-sm-6">
									<label><?php echo trans($permission['permission_section']);?></label>
								</div>
								<div class="col-sm-6 ">
									<div class="row">
										<div class="col-md-3 col-sm-3 col-xs-6">
											<div class="checkbox checkbox-success checkbox-inline">
												<input type="checkbox" checkbox_type="view" name="<?php echo $permission['permission_section'].'_view';?>" id='chk_box<?php echo $count;?>1' value="1"
												<?php 
													if(isset($this->route['id'])){
														if(in_array($user_id, explode(',',$permission['permission_view']))) echo "checked";
													}
												?>
												/> 
												<label for="chk_box<?php echo $count;?>1"> <?php echo trans('view');?> </label>
											</div>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-6">
											<div class="checkbox checkbox-primary checkbox-inline">
												<input type="checkbox" checkbox_type="edit" name="<?php echo $permission['permission_section'].'_edit';?>" id='chk_box<?php echo $count;?>2' value="1" 
												<?php 
													if(isset($this->route['id'])){
														if(in_array($user_id, explode(',',$permission['permission_edit']))) echo "checked";
													}
												?>
												/> 
												<label for="chk_box<?php echo $count;?>2"> <?php echo trans('edit');?> </label>
											</div>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-6">
											<div class="checkbox checkbox-danger checkbox-inline">
												<input type="checkbox" checkbox_type="delete" name="<?php echo $permission['permission_section'].'_delete';?>" id='chk_box<?php echo $count;?>3' value="1" 
												<?php 
													if(isset($this->route['id'])){
														if(in_array($user_id, explode(',',$permission['permission_delete']))) echo "checked";
													}
												?>
												/>
												<label for="chk_box<?php echo $count;?>3"> <?php echo trans('delete');?> </label>
											</div>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-6">
											<div class="checkbox checkbox-warning checkbox-inline">
												<input type="checkbox" checkbox_type = "off" name="<?php echo $permission['permission_section'].'_off';?>" id='chk_box<?php echo $count;?>4' value="1" onclick="OffChkBoxDisabled(this,<?php echo $count;?>)"
												<?php 
													if(isset($this->route['id'])){
														if(in_array($user_id, explode(',',$permission['permission_off']))) echo "checked";
													}
												?>
												/> 
												<label for="chk_box<?php echo $count;?>4"> <?php echo trans('off');?> </label>
											</div>
										</div>
										
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						</br>
						<?php
						$count++;
						} ?>
						<div class="switch row">
							<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit">
								<strong>
									<?php echo trans('save_changes'); ?>
								</strong>
							</button>
						</div>
					</div>
	</div>
</div>
</form>
</div>

</div>
[footer]
<?php 
	getJs('assets/system/js/plugins/iCheck/icheck.min.js',false,false); 
	getJs('assets/system/js/plugins/chosen/chosen.jquery.js',false,false); 
?>
<script type="text/javascript">
	
	$('.manage_user form').validate({
		rules: {
			first_name: {
				required: true
			},
			last_name: {
				required: true
			},
			user_mobile: {
				required: true
			},
			user_email: {
				required: true
			},
			user_address: {
				required: true
			},
			store_id: {
				required: true
			},
			user_password: {
				minlength: 6,
				maxlength: 10
			},
			confirm_password: {
				minlength: 6,
				maxlength: 10
			}
		},
		submitHandler: function(form) {
			AS.Http.PostSubmit(form, {"action" : "ManageUserSubmit"}, "pos/ajax/", function (response) {
				if(response.status=='success'){
					
					swal({
						title: $_lang.success, 
						text: '', 
						type: "success",
						confirmButtonColor: "#1ab394", 
						confirmButtonText: $_lang.ok,
					});
				}
			});
		}
	});
	
	function getcountrycode(){
		var countrycode = $("#country_code").val();
		$("#user_mobile").val(countrycode);
	}
	$(document).on("click",".confirm", function(){
		window.location.reload();
	});
	
	function value_update(e,flag)
	{
		var ischecked= $(e).is(':checked');
		$(e).val('0');
		if(ischecked){
			$(e).val('1');
		}
	}
	
	function OffChkBoxDisabled(e,flag){
		
		var ischecked= $(e).is(':checked');
		var checkbox_type = $(e).attr('checkbox_type');
		
		
		let view = $('#chk_box'+flag+'1');
		let edit = $('#chk_box'+flag+'2');
		let del = $('#chk_box'+flag+'3');
		
		view.prop("disabled",true);
		edit.prop("disabled",true);
		del.prop("disabled",true);
		
		if(ischecked && checkbox_type=='off'){
			//disabled checkbox when off checkbox is checked
			view.prop("disabled",true);
			edit.prop("disabled",true);
			del.prop("disabled",true);
			
			//unchecked checkbox when off checkbox is checked
			view.prop("checked",false);
			edit.prop("checked",false);
			del.prop("checked",false);
			
			//update checkbox value
			value_update(e,flag);
			value_update(view,flag);
			value_update(edit,flag);
			value_update(del,flag);
		}
		else{
			view.prop("disabled",false);
			edit.prop("disabled",false);
			del.prop("disabled",false);
			
		}
	}	
</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>														