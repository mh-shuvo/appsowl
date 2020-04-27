<?php defined('_AZ') or die('Restricted access'); 
	if (app('login')->isLoggedIn()) {
		if($this->currentUser->is_user) {
			$subdomaincheck = app('admin')->getwhereid('as_sub_domain','domain_id',$this->currentUser->domain_id);
			if($subdomaincheck){
				redirect($protocol.$subdomaincheck['sub_domain'].'.'.$subdomaincheck['root_domain']);
				}else{
				redirect("/logout/");
			}
			}elseif($this->currentUser->is_agent && $this->currentUser->sms_confirmed == "Y"){
			redirect("/agent/");
			}elseif($this->currentUser->sms_confirmed == "Y"){
			redirect("/dashboard");
		}
	}
	
?>
<!DOCTYPE html>
<html>
	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<base href="<?php echo WEBSITE_DOMAIN; ?>/" >
		<title>Apps | Owl</title>
		
		<!-- Bootstrap core CSS 
			<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
		-->
		
        <?php echo getCss('assets/system/css/bootstrap.min.css',false);?>
        <?php echo getCss('assets/system/css/animate.css');?>
        <?php echo getCss('assets/system/css/style.css');?>
        <?php echo getCss('//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css',false,false);?>
        <?php echo getCss('assets/system/css/prettyPhoto.css',false,false);?>
        <?php echo getCss('assets/system/css/main.css',false,false);?>
        <?php echo getCss('assets/system/css/transitions.css',false,false);?>
        <?php echo getCss('assets/system/css/responsive.css',false,false);?>
        <?php echo getCss('assets/system/css/plugins/sweetalert/sweetalert.css');?>
        <?php echo getCss('https://use.fontawesome.com/releases/v5.3.1/css/all.css');?>
		<style type="text/css">
			.btn-default{background: #1ab394;color:white; font-weight: bold;border-color: #1ab394;color: #FFFFFF;}
			.btn-default:hover{background: #179d82;border-color: #179d82;color: #FFFFFF;}
			.bg-color{background: white;}
			
			/* start Tutorial Gallary*/
			.pb-video {
            border: 1px solid #e6e6e6;
            padding: 5px;
			}
			
            .pb-video:hover {
			background: #2c3e50;
			}
			
			.pb-video-frame {
			transition: width 2s, height 2s;
			}
			
			.pb-row {
			margin-bottom: 10px;
			}
			.domain_form{margin-top:100px;}
			
			#basic-addon2{font-size:16px;background:#f4f4f4;}
			.search_sdomain{float:left;margin-top:60px;}
			.search_btn{height:50px;width:200px;}
			.next_btn{height:30px;font-size:14px;}
			.next_btn i{margin-left:10px; }
			.loader{
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 9999;
			background: url('assets/system/img/loading.gif') 50% 50% no-repeat rgb(249,249,249);
			opacity: .8;
			}
			.progress {
			margin: 10px;
			width: 700px;
			}
			
		</style>
		<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	</head>
	
	<body class="landing-page  white-section">
		<section id="nav-section">
			<div class="navbar-wrapper">
				<nav class="navbar navbar-default white-section navbar-fixed-top" role="navigation">
					<div class="navbar-header page-scroll">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="/">Apps Owl</a>
						
					</div>
					<div id="navbar" class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							<li><a class="page-scroll" href="/">Home</a></li>
							
							<li><a class="page-scroll" href="pricing">Pricing</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Service<span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="pos-details?category_name=wholesell">Whole Seller POS</a></li>
									<li class="divider"></li>
									<li><a href="pos-details?category_name=departmental">Departmental Store POS</a></li>
									<li class="divider"></li>
									<li><a href="pos-details?category_name=fashion">Fashion House POS</a></li>
									<li class="divider"></li>
									<li><a href="pos-details?category_name=electronics">Electronics POS</a></li>
									<!--<li class="divider"></li>
									<li><a href="pos-details?category_name=restaurant">Restaurant POS</a></li>
									<li class="divider"></li>                
									<li><a href="pos-details?category_name=cosmetics">Cosmetics POS</a></li>
									<li class="divider"></li>
									<li><a href="pos-details?category_name=jewelry">Jewelry Store POS</a></li>
									<li class="divider"></li>
									<li><a href="pos-details?category_name=sharee">Sharee and Boutique POS</a></li>
									<li class="divider"></li>
									<li><a href="pos-details?category_name=building">Building Materials POS</a></li>
									<li class="divider"></li>
									<li><a href="pos-details?category_name=gym">GYM & Fitness Center POS</a></li>
									<li class="divider"></li>
									<li><a href="pos-details?category_name=saloon">Saloon POS</a></li>
									<li class="divider"></li>
									<li><a href="pos-details?category_name=parlor">Parlor POS</a></li>
									<li class="divider"></li>-->
								</ul>
							</li>
							<li><a class="page-scroll" href="tutorial">Tutorial</a></li>
							<li><a class="page-scroll" href="blog">Blog</a></li>
							<li><a class="page-scroll" href="#contact">Contact</a></li>
						</ul>
					</div>
				</nav>
			</div><hr>
		</section>
		