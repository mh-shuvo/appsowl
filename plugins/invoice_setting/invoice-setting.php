<?php defined('_AZ') or die('Restricted access'); 

	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
$invoice_info=app('admin')->getwhereid('pos_invoice','id','1');
$invoice_id='';
if($invoice_info!=null){
	$invoice_id=$invoice_info['invoice_id'];
}
	?>
    [header]
    <style type="text/css">
        .thumbnail {
            height: 70%;
            width: 70%;
            cursor: pointer;
        }
        .invoice-details{margin-top:5px;}
        .invoice-details p{font-size:14px;}
		.panel-title{font-size:24px;}
    </style>
    [/header]
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-4">

            <h2><?php echo trans('invoice-setting'); ?></h2>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="row">
						<div class="col-sm-4 text-center">
							<div class="panel panel-primary">
							  <div class="panel-heading">
								 <h3 class="panel-title">Invoice 1</h3>
							  </div>
							  <div class="panel-body">
								 <div>
									<img src="assets/img/avatar.jpg" class="img-rounded">
								</div>
								<div class="text-center invoice-details">
									<p class="font-bold">
									An invoice, bill or tab is a commercial document issued by a seller to a buyer,
									relating to a sale transaction and indicating the products, quantities, 
									and agreed prices for products or services the seller had provided the buyer.
									</p>
								</div>
								<div class="button row">
								<?php if($invoice_id=='INV-1' && $invoice_info['status']=='active'){?>
									<div class="col-sm-12">
										<button class="btn btn-danger btn-block" disabled inv-id='INV-1'><?php echo trans('activated');?></button>
									</div>
								<?php }else{?>
									<div class="col-sm-12">
										<button class="btn btn-primary btn-block invoice_active" inv-id='INV-1'><?php echo trans('activate');?></button>
									</div>
								<?php }?>
								</div>
							  </div>
							</div>
						</div>
						<div class="col-sm-4 text-center">
							<div class="panel panel-primary">
							  <div class="panel-heading">
								 <h3 class="panel-title">Invoice 2</h3>
							  </div>
							  <div class="panel-body">
								 <div>
									<img src="assets/img/avatar.jpg" class="img-rounded">
								</div>
								<div class="text-center invoice-details">
									<p class="font-bold">
									An invoice, bill or tab is a commercial document issued by a seller to a buyer,
									relating to a sale transaction and indicating the products, quantities, 
									and agreed prices for products or services the seller had provided the buyer.
									</p>
								</div>
								<div class="button row">
					
									<?php if($invoice_id=='INV-2' && $invoice_info['status']=='active'){?>
									<div class="col-sm-12">
										<button class="btn btn-danger btn-block" disabled inv-id='INV-2'><?php echo trans('activated');?></button>
									</div>
								<?php }else{?>
									<div class="col-sm-12">
										<button class="btn btn-primary btn-block invoice_active" inv-id='INV-2'><?php echo trans('activate');?></button>
									</div>
								<?php }?>
								</div>
							  </div>
							</div>
						</div>
						<div class="col-sm-4 text-center">
							<div class="panel panel-primary">
							  <div class="panel-heading">
								 <h3 class="panel-title">Invoice 3</h3>
							  </div>
							  <div class="panel-body">
								 <div>
									<img src="assets/img/avatar.jpg" class="img-rounded">
								</div>
								<div class="text-center invoice-details">
									<p class="font-bold">
									An invoice, bill or tab is a commercial document issued by a seller to a buyer,
									relating to a sale transaction and indicating the products, quantities, 
									and agreed prices for products or services the seller had provided the buyer.
									</p>
								</div>
								<div class="button row">
									<?php if($invoice_id == 'INV-3' && $invoice_info['status'] == 'active'){?>
									<div class="col-sm-12">
										<button class="btn btn-danger btn-block" disabled inv-id='INV-3'><?php echo trans('activated');?></button>
									</div>
								<?php }else{?>
									<div class="col-sm-12">
										<button class="btn btn-primary btn-block invoice_active" inv-id='INV-3'><?php echo trans('activate');?></button>
									</div>
								<?php }?>
								</div>
							  </div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>

    </div>
	<div class="ModalForm">
		<div class="modal_status"></div>
	</div>
    [footer]
    <script>
		$('.invoice_active').click(function(){
			// alert($(this).attr('inv-id'));
			$(".show_modal").remove();
			AS.Http.posthtml({"action" : "GetInvoiceSettingModal","id" : $(this).attr('inv-id')}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
		$('.invoice_deactive').click(function(){
			jQuery.ajax({
					url: "pos/ajax/",
					data: {
						action: "InvoiceStatusChange",
						id	  : $(this).attr('inv-id')
					},
					type: "POST",
					success:function(response){
						if(response.status=='success'){
							swal({
								title: $_lang.success, 
								text: response.message, 
								type: "success",
								confirmButtonColor: "#1ab394", 
								confirmButtonText: $_lang.ok,
							},function(isConfirm){
								if(isConfirm){
									location.reload();
								}
							});
						}
					},
					error:function (){}
				});
		});
    </script>
    [/footer]
    <?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php';
?>