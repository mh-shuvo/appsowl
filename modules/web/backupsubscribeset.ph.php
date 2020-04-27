	<div class="ibox-content" >                      
									<!-- id="dropzoneForm" -->
									<?php /*echo trans('pos_name'); */?>
									<div class="form-group">
										<label class="col-sm-3 control-label"><?php echo trans('company_name'); ?></label>
										<div class="col-sm-9">
											<input type="text" name="company_name" id="company_name" class="form-control" onkeyup='getcheckdomain()' placeholder="<?php echo trans('your_company_name'); ?>">
										</div>
									</div>
									<!--<div class="form-group">
										<label class="col-sm-3 control-label"><?php echo trans('company_logo'); ?></label>
										<div class="col-sm-9 fallback">

											<input type="file" name="company_logo" multiple />

										</div>
									</div>-->
									<div class="hr-line-dashed"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label"><?php echo trans('email'); ?></label>
										<div class="col-sm-9">
											<input type="email" name="email" id="email" class="form-control" placeholder="<?php echo trans('email'); ?>">
										</div>
									</div>
									<div class="hr-line-dashed"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label"><?php echo trans('phone'); ?></label>
										<div class="col-sm-9">
											<input type="text" name="phone" id="phone" class="form-control" placeholder="<?php echo trans('phone'); ?>">
										</div>
									</div>
									<div class="hr-line-dashed"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label"><?php echo trans('address'); ?></label>
										<div class="col-sm-9">
											<input type="text" name="address" id="address" class="form-control" placeholder="<?php echo trans('address'); ?>">
										</div>
									</div>
									<div class="hr-line-dashed"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label"><?php echo trans('website'); ?></label>
										<div class="col-sm-9">
											<input type="text" name="website" id="website" class="form-control" placeholder="<?php echo trans('website'); ?>">
										</div>
									</div>
							</div>