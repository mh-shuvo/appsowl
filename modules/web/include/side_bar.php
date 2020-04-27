<?php defined('_AZ') or die('Restricted access');
	if(isset($this->route['page'])){
		$url_segment = $this->route['page'];
		}else{
		$url_segment = null;
	}
	
?>
<body class="top-navigation gray-bg">
	
    <!-- <div id="wrapper" class=""> -->
	<div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
			<nav class="navbar navbar-static-top" role="navigation">
				<div class="navbar-header">
					<button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
						<i class="fa fa-reorder"></i>
					</button>
					<a href="#" class="navbar-brand"> <span style="padding: 8px;">Apps Owl</span></a>
				</div>
				<div class="navbar-collapse collapse" id="navbar">
					<ul class="nav navbar-nav">
						
                        <?php if($url_segment=="dashboard" ){?><li class="active"><?php }else{echo "<li>";} ?>
                            <a href="dashboard"><i class="fa fa-th-large"></i> <span class="nav-label"><?php echo trans('dashboard'); ?></span> </a>
						</li>
                        <?php if($url_segment=="account-setting" ){?><li class="active"><?php }else{echo "<li>";} ?>
                            <a href="account-setting"><i class="fa fa-wrench"></i> <span class="nav-label"><?php echo trans('my_profile'); ?></span></a>
						</li>
						<!-- <li>
                            <a href="pos-setting"><i class="fa fa-wrench"></i> <span class="nav-label"><?php echo trans('pos_setting'); ?></span></a>
							</li>
						-->
                        <?php if($url_segment =="history" ){?><li class="active"><?php }else{echo "<li>";} ?>
                            <a href="history"><i class="fa fa-history"></i> <span class="nav-label"><?php echo trans('subscription_history'); ?></a>
							</li>
							<?php if($url_segment =='payment-history'){?> <li class="active"><?php } else{ echo "<li>"?>
								<a href="/payment-history"><i class="fa fa-money"></i><span class="nav-label"><?php echo trans('payment_inovices'); ?></span></a>
							</li>
							<?php } ?>
							<li>
								<div class="navbar-header">
									<button class="btn btn-primary user_id">
									<span style="padding: 8px;"><?php echo trans('user_id'); ?> : <?php echo $this->currentUser->id; ?></span> </button>
								</div>
							</li>
							
						</ul>
						
						<ul class="nav navbar-top-links navbar-right">
							<li class="dropdown fund_dropdown">
								 <a href="javascript:void(0)" class="dropdown-toggle text-info" data-toggle="dropdown" style="color: #18a689;"><?php echo trans('current_balance'); ?> :<strong> <?php echo $currentFund['current_balance']; ?> </strong>  TK <b class="caret"></b></a>
								 <ul class="dropdown-menu">
									  <li><a href="/fund"><?php echo trans('fund');?></a></li>
									  <li><a href="/voucher"><?php echo trans('voucher');?></a></li>
									  <li><a href="/withdrawal"><?php echo trans('withdrawal');?></a></li>
								 </ul>
							</li>
							<?php 
								$total_unread = app('root')->select("SELECT * FROM `as_notification` WHERE `notification_type`='admin' OR `notification_type`='user' AND `read_status`='unread' AND `user_id` =:id  ORDER BY `notification_id` DESC LIMIT 5",array('id'=>$this->currentUser->id));
								$notifications = app('root')->select("SELECT * FROM `as_notification` WHERE `notification_type`='admin' OR `notification_type`='user' AND `user_id` =:id  ORDER BY `notification_id` DESC LIMIT 5",array('id'=>$this->currentUser->id));
								if(isset($notifications)){ ?>
								<li class="dropdown">
									<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
										<i class="fa fa-bell"></i>  <?php if(count($total_unread)!=0){?><span class="label label-warning total_notification" total_notification="<?php echo count($total_unread); ?>"><?php echo count($total_unread); ?></span><?php } ?>
									</a>
									<ul class="dropdown-menu dropdown-messages notifications_area" last_id="<?php echo $notifications[0]['notification_id']; ?>" user_id="<?php echo $this->currentUser->id; ?>" style="height:300px; overflow-y:scroll;">
										<?php foreach($notifications as $notification){ ?>
										<li>
										<a  n_id = "<?php echo $notification['notification_id'];?>" href="<?php  echo $notification['link']!=null ? $notification['link']:'javascript:void(0)'; ?>" class="<?php  echo $notification['read_status']=='unread' ? 'change_read_status':''; ?>" <?php  echo $notification['read_status']=='unread' ? 'style="background:#e7f3f6"':''; ?>>
												<div class="media-body">
													<strong><?php echo $notification['title']; ?></strong><br>
													<p><strong class="text-muted"><?php echo $notification['message']; ?></strong></p>
													<small class="text-muted"><?php echo getdatetime($notification['created_at'],2); ?></small>
												</div>
										</a>
										</li>
										<li class="divider"></li>
										<?php }  ?>
									</ul>
								</li>
								<?php } ?>
							<?php if(ASLang::getLanguage() == 'en'){?>
								<li>
									<a href="?lang=bn" class="mr-1">
										<label class='label label-primary'>Bangla</label>
									</a>
								</li>
								<?php }elseif(ASLang::getLanguage() == 'bn'){?>
								<li>
									<a href="?lang=en" class="mr-1">
										<label class='label label-danger'>English</label>
									</a>
								</li>
							<?php } ?>
							<li>
								<?php if (! app('login')->isLoggedIn()) {
								?>
								<a href="login.php">
									<i class="fas fa-sign-out-alt"></i> <?php echo trans('login'); ?>
								</a>
								<?php 
								}else{ ?>
								<a onclick='logout_confirmation()' >
									<span class="btn btn-danger btn-sm"> <i class="fas fa-sign-out-alt"></i> <?php echo trans('logout'); ?></span>
								</a>
								<?php } ?>
							</li>
						</ul>
					</div>
				</nav>
			</div>
																												