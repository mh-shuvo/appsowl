<?php defined('_AZ') or die('Restricted access'); 
	if (! app('login')->isLoggedIn()) {
		redirect("/");
	}
	if($_SERVER['HTTP_HOST'] != ROOT_DOMAIN) redirect($protocol.ROOT_DOMAIN);
	
	if ($this->currentUser->is_user) {
		$subdomaincheck = app('admin')->getwhereid('as_sub_domain','domain_id',$this->currentUser->domain_id);
		if($subdomaincheck){
			redirect($protocol.$subdomaincheck['sub_domain'].'.'.$subdomaincheck['root_domain']);
			}else{
			redirect("/logout/");
		}
	}
	
	if ($this->currentUser->sms_confirmed == "N") {
		redirect("/verify/");
	}
	
	if ($this->currentUser->is_agent) {
		redirect("/agent/");
	}
	// app('admin')->userPluginsJsonUpdate($this->currentUser->id);
	$currentFund = app('admin')->currentFund($this->currentUser->id);
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<base href="<?php echo WEBSITE_DOMAIN; ?>/" >
		<title>Appsowl | Dashboard</title>
		
		
		<?php
			echo getCss('assets/system/css/bootstrap.min.css',false);
			echo getCss('assets/system/font-awesome/css/font-awesome.css',false);
			echo getCss('assets/system/css/plugins/toastr/toastr.min.css',false);
			echo getCss('assets/system/js/plugins/gritter/jquery.gritter.css');
			echo getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
			echo getCss('assets/system/css/animate.css');
			echo getCss('assets/system/css/style.css');
			echo getCss('https://use.fontawesome.com/releases/v5.3.1/css/all.css',false,false);
			echo getCss('assets/system/css/plugins/datapicker/datepicker3.css');
			echo getCss('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.css');
		?>
		<style type="text/css">
			th{font-size: 14px;}
		td{font-size: 13px;}
		}
		</style>
		<?php
		echo getCss('assets/system/css/plugins/dropzone/basic.css');
		echo getCss('assets/system/css/plugins/dropzone/dropzone.css');
		echo getCss('assets/system/css/plugins/jasny/jasny-bootstrap.min.css',false);
		echo getCss('assets/system/css/plugins/codemirror/codemirror.css');
		echo getCss('assets/system/css/plugins/sweetalert/sweetalert.css');
		?>
		
		
		<!-- ========================= Page Saparate ============================== -->
		
		<script type="text/javascript">
		(function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = 'https://apis.google.com/js/plusone.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		})();
		
		
		</script>
		
		<style>
		#time_now{
		font-size: 20px;
		font-weight: bold;
		letter-spacing: 3px;
		}
		.user_id{margin-top:10px;}
		.demo label,b{font-size:14px;}
		.text-info{color: #18a689;}
		</style>
		
		</head>									