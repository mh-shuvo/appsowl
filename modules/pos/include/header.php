<?php defined('_AZ') or die('Restricted access'); 
	if (! app('login')->isLoggedIn()) {
	    redirect("/");
	}
	if (!app('admin')->softwareSubscribeCheck() && !isset($this->route['id']) && $this->route['id'] != 'point_of_sale') {
		redirect("/pos/plugins-status/point_of_sale");
	}
	// getActiveMenu($this->uri,'pos/supplier-view');
	// app('admin')->loadAddon('advance_pos','advance_pos_table',array('User' => $this->currentUser));
	// app('pos')->checkPermission('contact_customer','view',true) or die(redirect("/access-denied"););
	// exit;
    if (ASSession::get("user_lock_status")=="locked"){
        redirect("/pos/lock");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name='viewport' content='width=device-width, user-scalable=no'/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<base href="<?php echo WEBSITE_DOMAIN; ?>/" >
		<title>Point of Sale</title>
		<?php
			getCss('assets/system/css/bootstrap.min.css',false,false);
			getCss('assets/system/css/plugins/touchspin/jquery.bootstrap-touchspin.min.css',false);
			getCss('assets/system/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css');
			getCss('assets/system/font-awesome/css/font-awesome.css');
			getCss('assets/system/css/plugins/switchery/switchery.css');
			getCss('assets/system/css/plugins/toastr/toastr.min.css',false);
			getCss('assets/system/js/plugins/gritter/jquery.gritter.css',false);
			getCss('assets/system/css/animate.css');
			getCss('assets/system/css/style.css');
			getCss('https://use.fontawesome.com/releases/v5.3.1/css/all.css',false,false);
			getCss('assets/system/css/plugins/datapicker/datepicker3.css');
			getCss('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.css',false,false);
			getCss('assets/system/css/plugins/sweetalert/sweetalert.css');
			getCss('assets/system/css/plugins/touchspin/jquery.bootstrap-touchspin.min.css',false);
		?>
		<style type="text/css">
			th{
			font-size: 14px;
			}
			td{
			font-size: 13px;
			}
			#time_now{
			font-size: 20px;
			font-weight: bold;
			letter-spacing: 3px;
			}
			.current_balance_amount{color: #18a689;font-weight:bold;}
		</style>
	</head>				