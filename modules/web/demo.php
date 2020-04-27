<?php
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';
?>
<div class="wrapper wrapper-content">
	  
			<div>
				<div>

					<h1 class="logo-name">Demo Software</h1>

				</div>
				
				<form action="#" method="post">
                            <div class="form-group row">
                                <label for="email_address" class="col-md-4 col-form-label text-md-right"><?php echo trans('user'); ?></label>
	                                <div class="col-md-6">
	                                    <input type="text" id="email_address" class="form-control" name="email-address" required autofocus>
	                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right"><?php echo trans('password'); ?></label>
	                                <div class="col-md-6">
	                                    <input type="password" id="password" class="form-control" name="password" required>
	                                </div>
                            </div> 
                    		</div>
                    </form>


	</div>			
			
    
</div>

<?php include dirname(__FILE__) .'/include/footer.php';?> 

