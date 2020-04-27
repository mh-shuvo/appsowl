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
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="javascript:void(1)">
                <span style="padding: 8px;"><?php echo trans('user_id'); ?> : <?php echo $currentUser->id; ?></span> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <h1>Current Balance : 0.00TK</h1>
            </li>
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

    </nav>
</div>