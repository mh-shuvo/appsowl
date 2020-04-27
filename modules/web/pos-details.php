<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/includes/header.php';
$category=$_GET['category_name'];
$getCategoryInfo=app('admin')->getwhereid('software_category','pos_category_key',$category);
// print_r($getCategoryInfo);
?>
[header]
<style type="text/css">
	.pos_category{color:white;}
	.pos_category img{height:100px; width:100px;margin-top:20px;}
	.pos_category h1{font-weight: 700;font-size: 38px;letter-spacing: -1.5px;}
	.ibox{margin-top:20px;}
	.about_pos p{margin: 0 0 40px !important;text-align: justify !important;}
	.pos_details h3{color: #373737;font-size: 18px;font-weight: bold;margin-bottom: 0;text-align: left;text-transform: uppercase;}
	.module .col-sm-12 h2{color: #373737;font-size: 22px;font-weight: bold;margin-bottom: 0;text-align: center;text-transform: uppercase;margin-bottom:10px;}
	.module .col-sm-4 {background:#015795;border: 1px solid #063b6f;cursor: pointer;height: 200px;overflow: hidden;padding: 20px;transition: all 0.3s ease-in-out 0s; font-weight:bold; color:white;}
	.module .col-sm-4 i{font-size:72px;}
	.module .col-sm-4 p{font-size:15px; line-height: 60px;}

</style>
[/header]
<section class="white-section" style="height:300px; background: url('http://posspot.com/wp-content/themes/syntech-pos/images/bg2.jpg');background-position: 100% 50%; background-repeat: no-repeat;">
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4 pos_category text-center ">
			<?php 
				if(file_exists('assets/system/img/icon/category_icon/'.$getCategoryInfo['pos_category_icon'])){
			 ?>
			<img src="assets/system/img/icon/category_icon/<?php echo $getCategoryInfo['pos_category_icon']; ?>" class="rounded">
		<?php }else{ ?>
			<img src="assets/system/img/icon/category_icon/trading.png" class="rounded">
		<?php } ?>
			 <h1 class="text-uppercase shadow-lg p-3 mb-5 bg-white rounded">
			 	<?php 
			 	if(isset($getCategoryInfo['pos_category_name'])){
			 		echo $getCategoryInfo['pos_category_name']; 
			 		}
			 		?>
			 	</h1>
			 <p><?php
			 		if(isset($getCategoryInfo['pos_category_tagline'])){
			 		echo $getCategoryInfo['pos_category_tagline'];
			 		}
			   ?></p>
		</div>
	</div>
</section>
<section class="gray-section">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<div class="ibox">
					<div class="ibox-content">
						<div class="about_pos">
							<p><?php
							if(isset($getCategoryInfo['pos_category_about'])){
							 echo $getCategoryInfo['pos_category_about']; }?></p>
							
						</div>
						<div class="pos_details">
							<h3>The Key Features of out <span class='text-uppercase'><?php echo $getCategoryInfo['pos_category_name'];?></span> are:</h3></br>
							<p><?php if(isset($getCategoryInfo['pos_category_details'])){ echo $getCategoryInfo['pos_category_details']; }?></p>
						</div>
						<div class="module row text-center">
							<div class="col-sm-12">
								<h2>Module</h2>
							</div>
							<div class="col-sm-4">
								<i class="fa fa-user"></i>
								<p class="text-uppercase">User Management</p>
							</div>
							<div class="col-sm-4">
								<i class="fa fa-user"></i>
								<p class="text-uppercase">Product Management</p>
							</div>
							<div class="col-sm-4">
								<i class="fa fa-shopping-bag"></i>
								<p class="text-uppercase">Purchase Management</p>
							</div>
							<?php if($category!='fashion'){ ?>
							<div class="col-sm-4">
								<i class="fa fa-money"></i>
								<p class="text-uppercase">Accounts Management</p>
							</div>
						<?php }else{ ?>
							<div class="col-sm-4">
								<i class="fa fa-female"></i>
								<p class="text-uppercase">Tailoring</p>
							</div>
						<?php } ?>
							<div class="col-sm-4">
								<i class="fa fa-university"></i>
								<p class="text-uppercase">Account Setting</p>
							</div><div class="col-sm-4">
								<i class="fa fa-gift"></i>
								<p class="text-uppercase">Promotion Management</p>
							</div><div class="col-sm-4">
								<i class="fa fa-dollar"></i>
								<p class="text-uppercase">Sales Management</p>
							</div><div class="col-sm-4">
								<i class="fa fa-truck"></i>
								<p class="text-uppercase">Delivery Management</p>
							</div><div class="col-sm-4">
								<i class="fa fa-bar-chart"></i>
								<p class="text-uppercase">Report Management</p>
							</div>
							<div class="col-sm-4">
								<i class="fas fa-warehouse"></i>
								<p class="text-uppercase">Multiple WareHouse</p>
							</div><div class="col-sm-4">
								<i class="fa fa-home"></i>
								<p class="text-uppercase">Stock Management</p>
							</div><div class="col-sm-4">
								<i class="fa fa-bar-chart"></i>
								<p class="text-uppercase">Report Management</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</section>
   
<?php include dirname(__FILE__) .'/includes/footer.php'; ?>