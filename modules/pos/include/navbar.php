<?php defined('_AZ') or die('Restricted access'); 
	$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_segments = explode('/', $uri_path);
?>
<div id="page-wrapper" class="gray-bg">
	<div class="row border-bottom">
		<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
			<div class="navbar-header">
				<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="javascript:void(1)"><i class="fa fa-bars"></i> </a>
			</div>
			<?php $registerOpen = app('admin')->getwhereandid('pos_register_report','user_id',$this->currentUser->id,'register_status','open');
				if ($registerOpen){ ?>
				<div class="navbar-header navbar-top-links">
					<a class="minimalize-styl-2 btn btn-warning " href="/pos/registry">
						<span style="padding: 8px;"><?php echo "RG "; ?>: <?php echo getdatetime($registerOpen['register_open'],2); ?></span>
					</a>
				</div>
			<?php } ?>
			<div class="navbar-header">
				<?php if($uri_segments['2']=='pos_sales'||$uri_segments['2']=='simple-sales'){ ?>
					<a href="javascript:void(0);" class="minimalize-styl-2 btn btn-warning toggle-fullscreen"><i class="fa fa-expand"></i></a>
				<?php }  ?>
				<a class="minimalize-styl-2 btn btn-info " href="javascript:void(0);">
					<span style="padding: 8px;"><?php echo trans('user_id_no'); ?>: <?php echo $this->currentUser->id; ?></span>
				</a>
                <a class="minimalize-styl-2 btn btn-primary create_ticket" href="javascript:void(0);">
                    <span style="padding: 8px;"> <?php echo trans('create_ticket'); ?></span>
                </a>
				<?php if(app('admin')->checkAddon('add_pos_sales')){ ?>
				<a href="pos/pos-sales" class="minimalize-styl-2 btn btn-primary">
                    <span style="padding: 8px;"> <?php echo trans('pos'); ?></span>
                </a>
				<?php } ?>
				<div class="btn-group">
					<button data-toggle="dropdown" class="minimalize-styl-2 btn btn-primary dropdown-toggle" aria-expanded="false"><?php
					if(isset($this->currentUser->store_id) && $this->currentUser->store_id!=0){
						$currentStore = app("db")->table('pos_store')->where('store_id' , $this->currentUser->store_id )->get('store_name'); 
						// print_r(currentStore);
						echo $currentStore;
					}
					else{
						echo trans("select_business_location");
					}
					?> 
					<span class="caret"></span></button>
					<ul class="dropdown-menu">
						<?php
								$stores = app("db")->table('pos_store')->get();
								
								foreach($stores as $store){
						?>
						
						<li><a href="javascript:void(0)" data-store = "<?php echo $store['store_id'];?>" class="changeStore"><?php echo $this->currentUser->store_id == $store['store_id'] ? "<i class='fa fa-check pull-right m-t-xs text-success'></i>" : " "; ?>  <?php echo $store['store_name'];?></a></li>
						
						<?php } ?>
					</ul>
				</div>
				</div>
			<ul class="nav navbar-top-links navbar-right">
				
				<li>
					<h1 class="text-info" id="time_now"></h1>
				</li>
				
				<?php if (ASLang::getLanguage()== "en") { ?>
				<li>
					<a href="javascript:void(0);" class="mr-1 change_lang" id="<?php echo WEBSITE_DOMAIN; ?>/?lang=bn">
						<span class="label label-primary"><?php echo "Bangla";?></span>
					</a>
				</li>
				<?php } elseif (ASLang::getLanguage()=="bn") { ?>
				<li>
					<a href="javascript:void(0);" class="mr-1 change_lang" id="<?php echo WEBSITE_DOMAIN; ?>/?lang=en">
					<span class="label label-primary"><?php echo "English";?></span>
					</a>
				</li>
				<?php }
				
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
					<li>
						<a class="text-center">
							<span class="btn btn-primary btn-sm help_btn"> <i class="fas fa-question-circle"></i> </span>
						</a>
					</li>
					<li>
						<a class="" onclick="location.reload()" >
						<span class="btn btn-default btn-sm"> <i class="fas fa-refresh"></i></span>
						</a>
					</li>
					
					<li>
						<a class="logout">
						<span class="btn btn-danger btn-sm"> <i class="fas fa-sign-out-alt"></i> <?php echo trans('logout'); ?></span>
						</a>
					</li>
			</ul>
				
		</nav>
	</div>
												