<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/includes/header.php';
	if (!app('login')->isLoggedIn()) {
		redirect("/");
	}
	if (app('login')->isLoggedIn() && $currentUser->sms_confirmed == "N") {
		redirect("verify");
	}
	
	$domainstatuscheck = app('admin')->getwhereid('as_sub_domain','user_id',$currentUser->id);
	
	if (app('login')->isLoggedIn() && $currentUser->sms_confirmed == "Y" && $domainstatuscheck['domain_status'] == 'active') {
		redirect("http://".$domainstatuscheck['sub_domain'].'.'.ROOT_DOMAIN);
	}
	
?>

<section class="gray-section" style="min-height:850px;">
    <div class="container">
		<?php if(!$domainstatuscheck){ ?>
			<div class="col-sm-6 col-sm-offset-3" class="domain_form">
				<div class="domain_form" id="domain_checker" >
					<h1 class="text-center">Enter Your Business Name</h1>
					<br>
					<form class="form-horizontal">
						<div class="row">
							<div class='col-md-12'>
								<div class="from-group">
									<input type="text" name="domain_name" id="domain_name" class="form-control" placeholder="Find a new FREE Domain" onkeyup="getcheckdomain()" aria-describedby="basic-addon2" autofocus>
									<!--<span class="input-group-addon" id="basic-addon2">.appsowl.com</span>-->
								</div>
								<br>
								<div class="input-group">
									<!--<span class="text-center success h3 text-success">Your Domain Availabile</span><br>-->
									<div class="domainstatus"></div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="col-sm-3 domain_form">
				<div class="search_sdomain">
					<a class="btn btn-success" href="javascript:void(0);" onclick="getcheckdomain()">Check Availability</a>
				</div>
			</div>
			<?php }elseif($domainstatuscheck['domain_status'] == 'inactive'){ ?>
			<div class="col-sm-12 domain_form">		
				<div class="row">
					<div class="col-sm-8 col-md-offset-2">
						<div class="">
							<h2 class='text-center'>Your System Installing within 2 Miniute</h2>
						</div>
						<div class="progress">
						  <div id="dynamic" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
							<span id="current-progress"></span>
						  </div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		
	</div>
</section>

[footer]
<script>
    document.getElementById('domain_name').addEventListener('keypress', function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
        }
     });
</script>
[/footer]
 include dirname(__FILE__) .'/includes/footer.php'; ?>