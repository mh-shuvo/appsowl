<?php defined('_AZ') or die('Restricted access');
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
	
	$getProducts = app('db')->table('pos_product')->get();
	
?>
[header]
<?php 
	getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
?>
[/header]


<div class="row wrapper">
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-title">
				<h4><?php echo trans('print_barcode'); ?></h4>
			</div>
			<div class="ibox-content PrintBarcode">
				<form>
					<input type="hidden" name="<?= ASCsrf::TOKEN_NAME ?>" value="<?= ASCsrf::getToken() ?>" />
					<input type="hidden" name="action" value="MultiProductBarcodePrint" />
					<table class="table table-striped table-bordered text-center PrintBarcodeTable"></table>
					<button type="submit" class="btn btn-success pull-right" style="margin-top:-14px;"><i class="fa fa-print"></i> <?php echo trans('print_barcode');?></button>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="barcode_view" class="hidden"></div>

[footer]
<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false); ?>
<script>
	$(document).ready(function(){	
		$(document).on('click','.isBarcodePrint',function(){
			var Id = $(this).data('id');
			var flag = $(this).prop('checked');
			$("#product_quantity"+Id).attr('disabled','true');
			if(flag == true){
				$("#product_quantity"+Id).removeAttr('disabled');
			}
		});
		
		$('.PrintBarcode form').validate({
			submitHandler: function(form) {	
				var $formData = new FormData(form);
				$.ajax({
					url: "pos/ajax/",
					type: "POST",
					dataType: "HTML",
					data: $formData,
					contentType:false,
					cache:false,
					processData:false,
					success: function (data) {
						$("#barcode_view").html(data);	
						$.print("#barcode-print");
						$('.PrintBarcode form').trigger("reset");
					}
				});
			}
		});
		
		function TableDataColums(){
			return [
			{ "title": $_lang.product_id,"class": "text-center", data : 'sub_product_id' },
			{ "title": $_lang.product_name,"class": "text-center", data : 'product_name' },
			{ "title": $_lang.action,"class": "text-center",
				orderable: false, data : 'sub_product_id',
				render: function (data, type, row) {
					return '<input type="checkbox" name="product_id[]" value="'+row.sub_product_id+'" class="isBarcodePrint select_product" data-id = "'+row.sub_product_id+'" ><input type="hidden" name="product_name[]" value="'+row.product_name+'"><input type="hidden" name="product_price[]" value="'+row.sell_price+'"><input type="hidden" name="product_vat[]" value="'+row.product_vat+'"><input type="hidden" name="product_vat_type[]" value="'+row.product_vat_type+'">';
				}
			},
			{ "title": $_lang.number_of_copy,"class": "text-center", 
				orderable:false,
				render: function(data, type, row){
					return '<input type="text" disabled name="product_quantity[]" id="product_quantity'+row.sub_product_id+'" placeholder="'+$_lang.enter_number_of_copy+'">';
				}
			},
			];
		}	
		
		function GetTableData(Filtertype)
		{
			AS.Http.GetDataTable('.PrintBarcodeTable',TableDataColums(),{ action : "GetProductData"},"pos/filter/",Filtertype);
		}
		
		GetTableData(false);
	});
</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>	