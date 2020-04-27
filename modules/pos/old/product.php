<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
	
	if (!empty($_GET['all_checked']))
    $all_checked = true;
	if($currentUser->pos_stock=='off'){
		die('Restricted access');
	}
?>
[header]
<?php 
	echo getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
	echo getCss('assets/system/css/plugins/iCheck/custom.css');
?>
[/header]
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-4">
		<h2><?php echo trans('product'); ?></h2>
		<ol class="breadcrumb">
			<li>
				<a href="{{ url('/') }}"><?php echo trans('dashboard'); ?></a>
			</li>
			<li class="active">
				<strong><?php echo trans('product'); ?></strong>
			</li>
		</ol>
	</div>
    <div class="col-lg-8">
		<?php if($currentUser->pos_stock=='edit'){?>
			<div class="title-action">
				<a href="pos/purchase-new" class="btn btn-primary btn-sm" ><i class="fa fa-plus"></i> <?php echo trans('add_product'); ?></a>
			</div>
		<?php } ?>
        <div id="edit-product-form" class="modal fade" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12" id="product_form">
                                <h3 class="m-t-none m-b"><?php echo trans('edit_product'); ?></h3>
                                <form>
                                    <div class="form-group">
                                        <label><?php echo trans('product_code'); ?></label>
                                        <input type="hidden" name="product_insert" value="123">
                                        <input type="text" name="product_id" class="form-control" id="product_id">
									</div>
                                    <div class="form-group">
                                        <label><?php echo trans('product_name'); ?></label>
                                        <input type="text" name="product_name" class="form-control" id="product_name" > 
									</div>
                                    <div class="form-group">
                                        <label><?php echo trans('purchase_price_enter'); ?></label>
                                        <input type="text" name="purchase_price" class="form-control" id="purchase_price" >
									</div>
                                    <div class="form-group">
                                        <label><?php echo trans('sell_price'); ?></label>
                                        <input type="text" name="sell_price" class="form-control" id="sell_price"> 
									</div>
									<div class="form-group">
                                        <label><?php echo trans('vat')." (%)"; ?></label>
                                        <input type="text" name="vat" class="form-control" id="vat"> 
									</div>
                                    <div class="form-group">
                                        <label><?php echo trans('size'); ?></label>
                                        <input type="text" name="size" class="form-control" id="size"> 
									</div>
                                    <div class="form-group">
                                        <label><?php echo trans('unit'); ?></label>
                                        <select class="form-control" name="unit" id="unit">
											<option><?php echo trans('select_unit'); ?></option>
                                            <?php $pos_unit = app('admin')->getall('pos_unit'); 
												foreach($pos_unit as $pos_Unit){
													echo "<option value=".$pos_Unit['unit_id'].">".$pos_Unit['unit_name']."</option>";
												}?>
										</select>
									</div>
                                    <div class="form-group">
                                        <label><?php echo trans('category'); ?></label>
                                        <select class="form-control" name="category" id="category">
                                            <option><?php echo trans('select_category'); ?></option>
                                            <?php $pos_category = app('admin')->getall('pos_category'); 
												foreach($pos_category as $pos_Category){
													echo "<option value=".$pos_Category['category_id'].">".$pos_Category['category_name']."</option>";
												}?>
										</select> 
									</div> 
                                    <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('submit'); ?></strong></button>
									<a class="btn btn-sm btn-danger pull-right m-t-n-xs" onclick="location.reload();"><strong><?php echo trans('close'); ?></strong></a>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div id="barcode-count" class="modal fade" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="m-t-none m-b"><?php echo trans('barcode'); ?></h3>
								<!-- <h3 class="m-t-none m-b"><?php echo trans('number_of_copies'); ?></h3>-->   <!--copy_of_barcode-->
								<div class="postedstatus"></div>
                                <form method="post" enctype="multipart/form-data" id="UploadMedia" action="/pos/ajax/">
                                    <div class="form-group">
										<label><?php echo trans('number_of_copies'); ?></label>
										<!--<input type="hidden" name="action" id="action" value="PrintReceipt">-->
										<input type="hidden" name="action" id="action" value="PrintProductCode"> 
                                        <input type="hidden" name="return_url" value=<?php echo $current_url; ?>>
										<input type="hidden" name="<?= ASCsrf::getTokenName() ?>" value="<?= ASCsrf::getToken() ?>">
										<input type="hidden" name="product_code" id="code">
										<input type="hidden" name="product_name" id="pt_name">
										<input type="hidden" name="product_price" id="product_price">
										<input type="text" name="code_count" class="form-control" value="1">
									</div>
                                    <!-- <a href="javascript:void(0)" class="btn btn-sm btn-primary pull-left m-t-n-xs" onclick="getProductBarcode()"><strong>Print</strong></a> -->
                                    <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('pdf'); ?></strong></button>
									<button class="btn btn-sm btn-danger pull-right m-t-n-xs " type="submit"><strong><?php echo trans('print'); ?></strong></button>
									<button class="btn btn-sm btn-warning pull-right m-t-n-xs " type="buttton" data-dismiss="modal"><strong><?php echo trans('cancel'); ?></strong></button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo trans('total_product_table'); ?></h5>
				</div>
				<div class="ibox-content">
					
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover dataTables-example" >
							<thead>
								<tr>
									<th class="text-center"><?php echo trans('serial_no'); ?></th>
									<th><?php echo trans('code'); ?></th>
									<th><?php echo trans('name'); ?></th>
									<th class="text-center"><?php echo trans('purchase_price_enter'); ?></th>
									<th class="text-center"><?php echo trans('sell_price_enter'); ?></th>
									<th class="text-center"><?php echo trans('stock'); ?></th>
									<th class="text-center"><?php echo trans('sold'); ?></th>
									<th class="text-center"><?php echo trans('damaged'); ?></th>
									<th class="text-center"><?php echo trans('expire'); ?></th>
                                    <th class="text-center"><?php echo trans('vat')." (%)"; ?></th>
									<th class="text-center"><?php echo trans('unit'); ?></th>
									<th class="text-center"><?php echo trans('category'); ?></th>
									<th class="text-center"><?php echo trans('add_by'); ?></th>
									<?php if($currentUser->pos_stock=='edit'){?>
										<th class="text-center"><?php echo trans('action'); ?></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php $pos_product = app('admin')->getall('pos_product'); 
									$i = 1;
									$serial=0;
									foreach($pos_product as $pos_Product){
										$serial++;
										$getuserdetails = app('admin')->getuserdetails($pos_Product['user_id']);
										$getproductstock = app('pos')->GetProductStock($pos_Product['product_id']);
									?>
									<tr>
										<td class="text-center">
											<?php echo $serial; ?>
										</td>
										<td><?php echo $pos_Product['product_id']; ?></td>
										<td><?php echo $pos_Product['product_name']; ?></td>
										<td class="text-center text-danger"><strong><?php echo $pos_Product['purchase_price']; ?></strong></td>
										<td class="text-center text-danger"><strong><?php echo $pos_Product['sell_price']; ?></strong></td>
										<td class="text-center" ><?php echo $getproductstock['total_stock']; ?></td>
										<td class="text-center" ><?php echo $getproductstock['total_sales']; ?></td>
										<td class="text-center" ><?php echo $getproductstock['total_damage']; ?></td>
										<td class="text-center" ><?php
											$laststock_expired = app('admin')->getlastrowwhereand('pos_stock','product_id',$pos_Product['product_id'],'stock_type','in','id');
											echo $laststock_expired['expire_date'];
											// if($laststock_expired && !empty($laststock_expired['expire_date'])) echo getdatetime($laststock_expired['expire_date'], 3); 
											
										?></td>
										<td class="text-center" ><?php echo $pos_Product['product_vat']; ?>%</td>
										<td class="text-center" ><?php 
											$getunit = app('pos')->getunit($pos_Product['unit']);
										echo $getunit['unit_name']; ?></td>
										<td class="text-center" ><?php
											$getcategory = app('pos')->getcategory($pos_Product['category_id']);
										echo $getcategory['category_name']; ?></td>
										<td class="text-center"><?php echo $getuserdetails['first_name'].' '.$getuserdetails['last_name']; ?></td>
										<?php if($currentUser->pos_stock=='edit'){?>
											<td class="text-center">
												<a href="#barcode-count" data-toggle="modal" class="btn btn-info btn-xs code" product_code="<?php echo $pos_Product['product_id']; ?>" product_name="<?php echo $pos_Product['product_name']; ?>" product_price="<?php echo $pos_Product['sell_price']; ?>"><?php echo trans('barcode'); ?></a>
												<a href="#edit-product-form" data-toggle="modal" class="btn btn-primary btn-xs product_edit"
												product_id="<?php echo $pos_Product['product_id']; ?>" 
												product_name="<?php echo $pos_Product['product_name']; ?>"
												purchase_price="<?php echo $pos_Product['purchase_price']; ?>"
												sell_price="<?php echo $pos_Product['sell_price']; ?>"
												vat="<?php echo $pos_Product['product_vat']; ?>"
												pdt_size="<?php echo $pos_Product['size']; ?>"
												unit="<?php echo $pos_Product['unit']; ?>"
												category_id="<?php echo $pos_Product['category_id']; ?>"><?php echo trans('edit'); ?></a>
												<!-- <a href="javascript:void(0);" id="<?php echo $pos_Product['product_id']; ?>" class="btn btn-danger btn-xs m-b product_delete"><?php echo trans('delete'); ?></a> -->
											</td>
										<?php } ?>
									</tr>
								<?php }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
[footer]
<script type="text/javascript">
	$("body").on('click','.code',function(){
		var product_code = $(this).attr('product_code');
		$("#code").val(product_code);   
		var pt_name = $(this).attr('product_name');
		$("#pt_name").val(pt_name);
		var product_price = $(this).attr('product_price');
		$("#product_price").val(product_price);
	});
	
</script>
<script type="text/javascript">
	$("body").on('click','.product_edit',function(){
		var product_id = $(this).attr('product_id');
		$("#product_id").val(product_id);
		var product_name = $(this).attr('product_name');
		$("#product_name").val(product_name);
		var purchase_price = $(this).attr('purchase_price');
		$("#purchase_price").val(purchase_price);
		var sell_price = $(this).attr('sell_price');
		$("#sell_price").val(sell_price);
		var size = $(this).attr('pdt_size');
		$("#size").val(size);
		$("#sell_price").val(sell_price);
		var vat = $(this).attr('vat');
		$("#vat").val(vat);
		var unit = $(this).attr('unit');
		$("#unit").val(unit);
		var Category= $(this).attr('category_id');
		$("#category").val(Category);
		
	});
</script>
<?php
	echo getJs('assets/system/js/plugins/iCheck/icheck.min.js',false);
?>
<script>
	$(document).ready(function () {
		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
		});
	});
</script>
<?php
	echo getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
?>
<script>
	$(document).ready(function(){
		$('.dataTables-example').DataTable({
			pageLength: 25,
			responsive: true,
			dom: '<"html5buttons"B>lTfgitp',
			buttons: [
			{extend: 'excel', title: 'product'},
			{extend: 'pdf', title: 'product'},
			
			{extend: 'print',
				customize: function (win){
					$(win.document.body).addClass('white-bg');
					$(win.document.body).css('font-size', '10px');
					
					$(win.document.body).find('table')
					.addClass('compact')
					.css('font-size', 'inherit');
				}
			}
			]
			
		});
		
	});
	
</script>
[/footer]
<?php 
	
include dirname(__FILE__) .'/include/footer.php'; ?>
