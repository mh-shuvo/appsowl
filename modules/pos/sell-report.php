<?php defined('_AZ') or die('Restricted access');
app('pos')->checkPermission('report','view') or redirect("pos/access-denied");
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';
?>
[header]
<?php 
	echo getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false); 
	echo getCss('assets/system/css/plugins/daterangepicker/daterangepicker-bs3.css');
?>
<style type="text/css">
	.text-primary{color:#1ab394;}
</style>
[/header]
<div class="row wrapper">
	<div class="col-sm-12">
		<h3><?php echo trans('product_sale_report'); ?></h3>
	</div>
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-title">
				<h3 class="text-primary"><i class="fa fa-filter span-primary"></i><?php echo trans('filter'); ?></h3>
				<div class="row">
				<?php
					if(app('admin')->checkAddon('multiple_store_warehouse')){
				?>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">
								<?php echo trans('business_location'); ?>
							</label>
							<select class="form-control GetSalesReportChange" id="store_id" name="store_id">
								<option value="">All</option>
								<?php $posStores = app('admin')->getall('pos_store'); foreach($posStores as $posStore){	?>
									<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore['store_id'] == $this->currentUser->store_id) echo "selected"; ?>><?php echo $posStore['store_name']; ?></option>
								<?php } ?>
							</select>
						</div>	
					</div>
					<?php } ?>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label"><?php echo trans('date_range'); ?></label>
							<div class="input-group">
								<span class="input-group-addon"> <i class="fa fa-calendar span-primary"></i> </span>
								<input class="form-control GetSalesReportChange" type="text" name="daterange" id="reportrange" placeholder="<?php echo trans('custom_daterange'); ?>" />
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label"><?php echo trans('customer'); ?></label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user span-primary"></i></span>
								<select class="form-control GetSalesReportChange" id="customer_id">
									<option value=""><?php echo trans('select_customer'); ?></option>
									<?php 
										$getCustomer=app('admin')->getwhere('pos_contact','contact_type','customer');
										foreach ($getCustomer as $customer) {
										?>
										<option value="<?php echo $customer['contact_id']; ?>"><?php echo $customer['name']; ?></option>
										
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<table class="table table-striped table-bordered ProductSaleReportTableData" data-title="Sale Report">
					<tfoot>
						<tr class="bg-gray font-17 footer-total text-center">
							<td colspan="4"></td>
							<td><?php echo trans("total_quantity"); ?> :</td>
							<td></td>
							<td></td>
							<td colspan="2"><?php echo trans("total_sales"); ?> :</td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="ModalForm">
	<div class="modal_status"></div>
</div>  
<div class='last_receipt_view hidden'></div>

[footer]
<?php
	getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
	getJs('assets/system/js/plugins/fullcalendar/moment.min.js',false);
	getJs('assets/system/js/plugins/daterangepicker/daterangepicker.js');
?>
<script>
	$(document).on("click",".sales_view", function(){
		var sales_id = $(this).data('sales-id');
		$(".show_modal").remove(); 
		AS.Http.posthtml({"action" : "GetSalesViewModal","sales_id":sales_id}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	function TableDataColums(){
		return [
		{ "title": $_lang.product_id,"class": "text-center", data : 'sub_product_id' },
		{ "title": $_lang.product_name,"class": "text-center", data : 'pos_product/product_name' },
		{ "title": $_lang.invoice_no,"class": "text-center", 
			orderable: false,
			render: function (data, type, row) {
				return '<a href="javascript:void(0)" data-sales-id="'+row.sales_id+'" class="sales_view">'+row.sales_id+'</a>';
			}
		},
		{ "title": $_lang.customer_name,"class": "text-center", data : 'pos_contact/name' },
		{ "title": $_lang.date,"class": "text-center", data : 'created_at' },
		{ "title": $_lang.product_quantity,"class": "text-center product_quantity", data : 'product_quantity' },
		{ "title": $_lang.sales_price,"class": "text-center ", data : 'product_price' }, 
		{ "title": $_lang.product_discount,"class": "text-center", data : 'product_discount',
			orderable: false,
			render: function (data, type, row) {
				if(row.product_discount==null){
					return 0;
				}
				else{
					return row.product_discount;
				}
			}
		},
		{ "title": $_lang.product_vat_total,"class": "text-center", data : 'product_vat_total' },
		{ "title": $_lang.sales_subtotal,"class": "text-center product_subtotal", data : 'product_subtotal' },     
		];
	}
	
	
	function CustomDateRangeFilter(FilterType) {
		// var storeId= $('#store_id').val() || null;
		var customerId= $('#customer_id').val() || null;
		start = $('input#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
		end = $('input#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
		var storeId= $('#store_id').val() || null;
		AS.Http.GetDataTable('.ProductSaleReportTableData',TableDataColums(),{ action : "GetProductSalesReportData", between_action : "created_at", from_data : start, to_data : end, date_range : true, customer_id : customerId,store_id: storeId},"pos/filter/",FilterType,["product_quantity","product_subtotal"]);
			
	}
	
	$(document).on("change",".GetSalesReportChange", function(){
		CustomDateRangeFilter(true);
	});
	
	
	var start = moment().subtract(29, 'days');
	var end = moment();
	
	$("#reportrange").daterangepicker({
		startDate: start,
		endDate: end,
		ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		}
		}, function (start,end) {
		$("#reportrange").val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	});
	
	$('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	
	CustomDateRangeFilter(false);
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php';?>