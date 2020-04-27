<?php defined('_AZ') or die('Restricted access'); 
$page_header='
<style>
    #time_now{
		font-size: 20px;
		font-weight: bold;
		letter-spacing: 3px;
	}
</style>
';
?>
<div id="page-wrapper" class="gray-bg">
<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="javascript:void()"><i class="fa fa-bars"></i> </a>
        </div>
        <div class="navbar-header">
            <a class="minimalize-styl-2 btn btn-primary ">
                <span style="padding: 8px;"><?php echo trans('agent_id');?> : <?php echo $this->currentUser->id; ?></span> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <h1 class="text-info" id="time_now"></h1>
            </li>
			<!-- <li class="dropdown" wfd-id="86">
				<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
					<i class="fa fa-bell"></i> <span class="label label-primary" wfd-id="85">8</span>
				</a>
				<ul class="dropdown-menu dropdown-messages" wfd-id="87">
					<li wfd-id="99">
						<div class="dropdown-messages-box" wfd-id="100">
							<a href="profile.html" class="pull-left">
								<img alt="image" class="img-circle" src="img/a7.jpg">
							</a>
							<div class="media-body" wfd-id="101">
								<small class="pull-right">46h ago</small>
								<strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>.
								<br>
								<small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
							</div>
						</div>
					</li>
				</ul>
			</li> -->
			<?php if(ASLang::getLanguage() == 'en'){?>
            <li>
                <a href="?lang=bn" class="mr-1">
                    <!--<img src="assets/img/flags/en.png" alt="English" title="English" />-->
					<label class='label label-primary'>Bangla</label>
                </a>
            </li>
			<?php }elseif(ASLang::getLanguage() == 'bn'){?>
            <li>
                <a href="?lang=en" class="mr-1">
                   <!-- <img src="assets/img/flags/bn.png" alt="Banla" title="Bangla" />-->
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

    </nav>
</div>