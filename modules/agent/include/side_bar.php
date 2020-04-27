<?php defined('_AZ') or die('Restricted access');
	$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$url_segment = explode('/', $uri_path);
?>

<body class="pace-done">
	<div id="wrapper">
		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					
					<?php if($url_segment[2]=="home" ){?><li class="active"><?php }else{echo "<li>";} ?>
						<a href="agent/home"><i class="fa fa-th-large"></i> <span class="nav-label"><?php echo trans('dashboard'); ?></span> </a>
					</li>
					
					<?php if($url_segment[2]=="new-user" ){?><li class="active"><?php }else{echo "<li>";} ?>
						<a href="agent/new-user"><i class="fa fa-user"></i> <span class="nav-label"><?php echo trans('user_registration'); ?></span></a>
					</li>
					
					<?php if($url_segment[2]=="user-info" ){?><li class="active"><?php }else{echo "<li>";} ?>
						<a href="agent/user-info"><i class="fa fa-info-circle"></i> <span class="nav-label"><?php echo trans('user_info'); ?><?php //echo trans('user_info_history'); ?></span></a>
					</li>
					
					<?php if($url_segment[2]=="subscribe-history" ){?><li class="active"><?php }else{echo "<li>";} ?>
						<a href="agent/subscribe-history"><i class="fa fa-history"></i> <span class="nav-label"><?php echo trans('subscription_log'); ?></span></a>
					</li>
					
					<?php if($url_segment[2]=="profile" ){?><li class="active"><?php }else{echo "<li>";} ?>
						<a href="agent/profile"><i class="fa fa-wrench"></i> <span class="nav-label"><?php echo trans('my_profile'); ?></span></a>
					</li>
					
					<!--<?php if($url_segment[2]=="history" ){?><li class="active"><?php }else{echo "<li>";} ?>
						<a href="history"><i class="fa fa-history"></i> <span class="nav-label"><?php echo trans('subscription_history'); ?></a>
					</li>-->
					<?php if($url_segment[2]=="received-payment" || $url_segment[2]=="withdrwal-payment"){ ?><li class="active"><?php }else{echo "<li>";} ?>
						<a ><i class="fa fa-credit-card"></i> <span class="nav-label"><?php echo trans('agent_payment'); ?></span><span class="fa arrow"></span></a>
						<ul class="nav nav-second-level collapse">
							<?php if($url_segment[2]=="received-payment"){ ?><li class="active"><?php }else{echo "<li>";} ?>
								
								<a href="agent/received-payment"><span class="nav-label"><?php echo trans('received'); ?></span></a>
							</li>
							<?php if($url_segment[2]=="withdrwal-payment"){ ?><li class="active"><?php }else{echo "<li>";} ?>
								<a href="agent/withdrwal-payment"><span class="nav-label"><?php echo trans('withdrwal'); ?></span></a>
							</li>
						</ul>
					</li>
					
				</ul>
			</div>
		</nav>									