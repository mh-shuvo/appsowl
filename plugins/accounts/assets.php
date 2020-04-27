<?php defined('_AZ') or die('Restricted access');
	app('admin')->checkAddon('accounts',true);		
	app('pos')->checkPermission('accounts','view',true) or die(redirect("/pos/access-denied"));
		include dirname(__FILE__) .'/../../modules/pos/include/header.php';
		include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
		include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';		
	?>
	[header]
	<?php 
		getCss('assets/system/css/plugins/daterangepicker/daterangepicker-bs3.css');
		getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
	?>
	<style type="text/css">
		.add_btn{margin-top: -38px;}
	</style>
	[/header]
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="ibox">
						<div class="ibox-title">
							<h4> <?php echo trans("categories");?> </h4>
							<div class="btn-group add_btn pull-right">
								<button class="btn btn-success new_category" type="button"><?php echo trans("new_category"); ?></button>
							</div>
						 </div>
						<div class="ibox-content">
							<div class="row">
							<?php 
								for($i=1;$i<=14;$i++){
							?>
								<div class="col-sm-12 m-b-sm f-16 text-capitalize">
									<a href="javascript:void(0)" class="text-primary" > <span class="fa fa-folder m-r-sm"></span><?php echo "Assets Category ".$i; ?></a>
								</div>
								
							<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<div class="ibox">
						<div class="ibox-title">
							<h4> <?php echo trans("manage_assets");?> </h4>
							<div class="btn-group add_btn pull-right">
								<button class="btn btn-primary new_assets" type="button"><?php echo trans("add_an_asset"); ?></button>
							</div>
						 </div>
						<div class="ibox-content table-responsive">
							<table class="table table-striped table-bordered text-center">
								<thead>
									<tr>
										<th class="text-center"><?php echo trans("name");?></th>
										<th class="text-center"><?php echo trans("purchase_date");?></th>
										<th class="text-center"><?php echo trans("supported_until");?></th>
										<th class="text-center"><?php echo trans("price");?></th>
									</tr>
								</thead>
								<tbody>
								<?php 
									for($i=1;$i<=10;$i++){
								?>
									<tr>
										<td><a href="javascript:void(0)"><?php echo "Name ".$i;?></a></td>
										<td>17 August 2019</td>
										<td>16 August 2020</td>
										<td>%50000</td>
									</tr>
								<?php } ?>
								<tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
	</div>
	<div class="ModalForm">
		<div class="modal_status"></div>
	</div>
	<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php';?>		
	[footer]
	<?php 
		getJs("assets/system/js/plugins/dataTables/datatables.min.js",false);
		getJs('assets/system/js/plugins/fullcalendar/moment.min.js',false);
		getJs('assets/system/js/plugins/daterangepicker/daterangepicker.js');
	?>
	
	<script type="text/javascript">
		$(document).on("click",".new_category", function(){
			$(".show_modal").remove();
			AS.Http.posthtml({"action" : "AddNewAssetsCategory"}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
		$(document).on("click",".new_assets", function(){
			$(".show_modal").remove();
			AS.Http.posthtml({"action" : "AddNewAssets"}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
	</script>

	[/footer]	