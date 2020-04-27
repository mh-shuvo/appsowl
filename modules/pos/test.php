<?php defined('_AZ') or die('Restricted access');
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
?>
[header]
<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
	getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
?>
<style>
	
	/*.thumbnail img{height: 50px;}*/
	.fullwidth-modal-dialog {
	width: 85%;
	left:8%;
	height: auto;
	margin: 0;
	padding: 0;
	}
	
	.fullwidth-modal-content {
	height: auto;
	min-height: 100%;
	border-radius: 0;
	}
</style>

[/header]
<div class="row wrapper  page-heading AddProductForm">
	<div class="row">
		<div class="col-lg-4">
			<h2><?php echo trans('add_new_product'); ?></h2>
			<button class="btn btn-primary btn-sm test_modal" >Add Product</button>
		</div>
	</div>
	<!--div class="modal_status_extend"></div-->
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog fullwidth-modal-dialog" role="document">
			<div class="modal-content fullwidth-modal-content">
				<div class="modal-body modal_status_extend">
					...
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal_status"></div>
	<div id="payment_div" data-total-bill="5000"></div>
	
</div>
[footer]	
<script>
	$(document).ready(function(){
		AS.Http.posthtml({"action" : "GetPaymentModal"}, "pos/modal/", function (data) {
			$("#payment_div").html(data);
			// $(".modal_status_extend").html(data);
			// $("#myModal").modal("show");
			GetTotalTransaction();
		});
		
	});
	
</script>
<?php getJs('assets/system/js/plugins/iCheck/icheck.min.js');?>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php'; ?>																