<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
	$company_info = app("db")->table("pos_setting")->get(1);
	$store = app("db")->table('pos_store')->where('store_id',$this->currentUser->store_id)->get('store_name');
?>
[header]
<style>
	.wrapper .middle-box{
		max-width: 500px;
		margin-top: 150px;
	}
</style>
[/header]
			<div class="wrapper wrapper-content">
					<div class="middle-box text-center animated fadeInRightBig">
						<h1 class="font-bold">Welcome to <?php echo $company_info['company_name'];?></h1>
						<div class="error-desc font-bold">
							<?php echo trans("store");?>: <span class="m-l-sm"><?php echo $store; ?></span> </br>
							<?php echo trans('company_email'); ?>: <span class="m-l-sm"><?php echo $company_info['email'];?></span> </br>
							 <span class="m-l-sm"><?php echo $company_info['address'];?></span>
							<br/><a href="pos/dashboard" class="btn btn-primary m-t"><?php echo trans('dashboard'); ?></a>
						</div>
					</div>
				</div>
<?php include dirname(__FILE__) .'/include/footer.php'; ?>								