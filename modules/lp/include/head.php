<?php defined('_AZ') or die('Restricted access'); 
	if (app('login')->isLoggedIn()) {
		redirect("/pos/");
	}
	
?>
<!doctype html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="author" content="SemiColonWeb" />
		<base href="<?php echo WEBSITE_DOMAIN; ?>/" >
		
		<!-- Stylesheets
		============================================= -->
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700|Roboto:300,400,500,700" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="assets/lp/css/bootstrap.css" type="text/css" />
		<link rel="stylesheet" href="assets/lp/css/style.css" type="text/css" />
		
		<!-- One Page Module Specific Stylesheet -->
		<link rel="stylesheet" href="assets/lp/css/onepage.css" type="text/css" />
		<!-- / -->
		
		<link rel="stylesheet" href="assets/lp/css/dark.css" type="text/css" />
		<link rel="stylesheet" href="assets/lp/css/font-icons.css" type="text/css" />
		<link rel="stylesheet" href="assets/lp/css/et-line.css" type="text/css" />
		<link rel="stylesheet" href="assets/lp/css/animate.css" type="text/css" />
		<link rel="stylesheet" href="assets/lp/css/magnific-popup.css" type="text/css" />
		
		<link rel="stylesheet" href="assets/lp/css/fonts.css" type="text/css" />
		
		<link rel="stylesheet" href="assets/lp/css/responsive.css" type="text/css" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		
		
		<!-- Document Title
		============================================= -->
		<title>Point of Sale</title>
		
	</head>						