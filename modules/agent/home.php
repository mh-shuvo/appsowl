<?php defined('_AZ') or die('Restricted access'); 
	$key = md5(date('ymdhi'));
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
    $agent_user = count(app('admin')->getwhere('as_user_details','agent_id',$this->currentUser->id));
    $active_user = count(app('admin')->getwhereand('as_subscribe','agent_id',$this->currentUser->id,'subscribe_status','active'));
    $agent_received = app('admin')->getsumtotalbywhereand('as_agent_payment','payment_amount','payment_type','receive','agent_id',$this->currentUser->id);
    $agent_withdrawal = app('admin')->getsumtotalbywhereand('as_agent_payment','payment_amount','payment_type','withdraw','agent_id',$this->currentUser->id);
    $agent_availiable_balance = $agent_received - $agent_withdrawal;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
		
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right"></span>
                    <h5><?php echo trans('total_user'); ?></h5>
				</div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?php echo $agent_user; ?></h1>
                    <small><?php echo trans('total_registered_user'); ?></small>
				</div>
			</div>
		</div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-info pull-right"></span>
                    <h5><?php echo trans('active_user'); ?></h5>
				</div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?php echo $active_user; ?></h1>
                    <!-- <div class="stat-percent font-bold text-info">20% <i class="fa fa-level-up"></i></div> -->
                    <small><?php echo trans('total_active_user'); ?></small>
				</div>
			</div>
		</div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right"></span>
                    <h5><?php echo  trans('inactive_user'); ?></h5>
				</div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?php echo $agent_user-$active_user; ?></h1>
                    <!-- <div class="stat-percent font-bold text-navy">44% <i class="fa fa-level-up"></i></div> -->
                    <small><?php echo trans('total_inactive_user'); ?></small>
				</div>
			</div>
		</div>
    	<!-- <div class="col-lg-3">
            <div class="ibox float-e-margins">
			<div class="ibox-title">
			<span class="label label-primary pull-right"></span>
			<h5><?php echo  trans('new_support'); ?></h5>
			</div>
			<div class="ibox-content">
			<h1 class="no-margins"><?php echo "30"; ?></h1>
			<small><?php echo "Total New Support"; ?></small>
			</div>
    		</div>
		</div> -->
    	<!-- <div class="col-lg-3">
            <div class="ibox float-e-margins">
			<div class="ibox-title">
			<span class="label label-primary pull-right"></span>
			<h5><?php echo  trans('support_complete'); ?></h5>
			</div>
			<div class="ibox-content">
			<h1 class="no-margins"><?php echo "30"; ?></h1>
			<small><?php echo "Total Completed Support"; ?></small>
			</div>
    		</div>
		</div> -->
    	<div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right"></span>
                    <h5><?php echo trans('available_bill'); ?></h5>
				</div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?php echo $agent_availiable_balance; ?></h1>
                    <!-- <div class="stat-percent font-bold text-navy">44% <i class="fa fa-level-up"></i></div> -->
                    <small><?php echo trans('total_available_bill'); ?></small>
				</div>
			</div>
		</div>
    	<div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right"></span>
                    <h5><?php echo  trans('paid_bill'); ?></h5>
				</div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?php if($agent_withdrawal==0){echo "0";}else{echo $agent_withdrawal;}
					?></h1>
                    <small><?php echo trans('total_paid_bill'); ?></small>
				</div>
			</div>
		</div>
    	<div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right"></span>
                    <h5><?php echo  trans('earn'); ?></h5>
				</div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?php if($agent_received==0){echo "0";}else{echo $agent_received;} ?></h1>
                    <small><?php echo trans('total_earn'); ?></small>
				</div>
			</div>
		</div>
	</div>
    <!-- <div class="row" wfd-id="117">
        <div class="col-lg-12" wfd-id="137">
		<div class="ibox float-e-margins" wfd-id="138">
		<div class="ibox-content" wfd-id="139">
		<div wfd-id="153">
		<span class="pull-right text-right" wfd-id="154">
		<small>Average value of sales in the past month in: <strong>United states</strong></small>
		<br>All sales: 162,862
		</span>
		<h3 class="font-bold no-margins">Half-year revenue margin</h3>
		<small>Sales marketing.</small>
		</div>
		
		<div class="m-t-sm" wfd-id="141">
		
		<div class="row" wfd-id="142">
		<div class="col-md-8" wfd-id="151">
		<div wfd-id="152">
		<iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px; height: 0px; margin: 0px; position: absolute; left: 0px; right: 0px; top: 0px; bottom: 0px;"></iframe>
		<canvas id="lineChart" height="259" width="682" style="display: block; width: 682px; height: 259px;"></canvas>
		</div>
		</div>
		<div class="col-md-4" wfd-id="143">
		<ul class="stat-list m-t-lg" wfd-id="144">
		<li wfd-id="148">
		<h2 class="no-margins">2,346</h2>
		<small>Total orders in period</small>
		<div class="progress progress-mini" wfd-id="149">
		<div class="progress-bar" style="width: 48%;" wfd-id="150"></div>
		</div>
		</li>
		<li wfd-id="145">
		<h2 class="no-margins ">4,422</h2>
		<small>Orders in last month</small>
		<div class="progress progress-mini" wfd-id="146">
		<div class="progress-bar" style="width: 60%;" wfd-id="147"></div>
		</div>
		</li>
		</ul>
		</div>
		</div>
		</div>
		</div>
		</div>
        </div>
	</div> -->
</div>
<?php include dirname(__FILE__) .'/include/footer.php'; ?>