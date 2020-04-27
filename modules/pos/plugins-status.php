<?php
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
	
	$adapter = new League\Flysystem\Adapter\Local(__DIR__.'/../../');
	$filesystem = new League\Flysystem\Filesystem($adapter);
	$SubDomainName = str_replace(".", "", $_SERVER['SERVER_NAME']);
	$jsonPluginLocation = 'user-config/'.$SubDomainName.'.json';
	$jsonPlugin = $filesystem->read($jsonPluginLocation);
	$jsonPluginData = json_decode($jsonPlugin);
	$pluginSexists = $filesystem->has($jsonPluginLocation);
	if(!isset($this->route['id'])){
		redirect("pos/access-denied");
		}else{
		$pluginName = $this->route['id'];
	}
	
	if(isset($jsonPluginData->addons->$pluginName)){
		$pluginType = "addons";
		}elseif(isset($jsonPluginData->plugins->$pluginName)){
		$pluginType = "plugins";
		}elseif(isset($jsonPluginData->software->$pluginName)){
		$pluginType = "software";
		}else{
		redirect("pos/access-denied");
	}
?>
<div class="middle-box text-center animated fadeInDown">
	<h1><?php echo ucfirst($pluginType); ?> Not Active</h1>
	<h3 class="font-bold">Please contact administrator for active <?php echo ucfirst($pluginType); ?></h3>
	
	<div class="error-desc">
		<p><?php echo ucfirst($pluginType); ?> Title : <strong><?php echo $jsonPluginData->$pluginType->$pluginName->name; ?></strong></p>
		<p><?php echo ucfirst($pluginType); ?> Subscribe Date : <strong><?php echo $jsonPluginData->$pluginType->$pluginName->subscribe_date; ?></strong></p>
		<p><?php echo ucfirst($pluginType); ?> Renewal Date : <strong><?php echo $jsonPluginData->$pluginType->$pluginName->renew_date; ?></strong></p>
		<p><?php echo ucfirst($pluginType); ?> Subscribe Status : <strong><?php echo $jsonPluginData->$pluginType->$pluginName->subscribe_status; ?></strong></p>
		<p></p>
		<br/>
		<br/>
		<a href="/" class="btn btn-primary">Back to Home Page</a>
	</div>
</div>
<?php include dirname(__FILE__) .'/include/footer.php'; ?>												