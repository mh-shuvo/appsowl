<?php defined('_AZ') or die('Restricted access');
if(isset($_GET['page'])){
    $url_segment = $_GET['page'];
}else{
    $url_segment = null;
}?>

    <body class="pace-done">
        <div id="wrapper">
            <nav class="navbar-default navbar-static-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav metismenu" id="side-menu">

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

                        <?php if($url_segment=="history" ){?><li class="active"><?php }else{echo "<li>";} ?>
                            <a href="history"><i class="fa fa-history"></i> <span class="nav-label"><?php echo trans('subscription_history'); ?></a>
                        </li>

                    </ul>
                </div>
            </nav>