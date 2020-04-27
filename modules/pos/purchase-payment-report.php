<?php defined('_AZ') or die('Restricted access');
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
		th{text-align: center;}
		.text-primary{color:#1ab394;}
		span i{color: #1ab394;}
	</style>
	<style type="text/css">
		th {
		text-align: center;
		}
		
		.add_btn {
		margin-top: -35px;
		}
		.modal-dialog {
		width: 85%;
		left:8%;
		height: auto;
		margin: 0;
		padding: 0;
		}
		
		.modal-content {
		height: auto;
		min-height: 100%;
		border-radius: 0;
		}
		.thumbnail img{height:100%; width:100%;}
		
	</style>
	[/header]
	<div class="row wrapper">
		<div class="col-sm-12">
			<h3><?php echo trans('purchase_payment_report'); ?></h3>
		</div>
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-title">
					<h3 class="text-primary"><i class="fa fa-filter"></i>  <?php echo trans('filter'); ?></h3>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label">
									<?php echo trans('location(to)'); ?>
								</label>
								<select class="form-control GetPurchaseReportChange" id="store_id">
								<option value="">All</option>
									<?php $posStores = app('admin')->getall('pos_store'); foreach($posStores as $posStore){	?>
										<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore['store_id'] == $this->currentUser->store_id) echo "selected"; ?>><?php echo $posStore['store_name']; ?></option>
									<?php } ?>
								</select>
							</div>	
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label"><?php echo trans('supplier'); ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									<select id="supplier_id" class="form-control select2 GetPurchaseReportChange">
										<option value=""><?php echo trans('select_supplier');?></option>
										<?php $getSuppliers = app('admin')->getwhere("pos_contact","contact_type","supplier"); foreach($getSuppliers as $getSupplier){ ?>
											<option value="<?php echo $getSupplier['contact_id']; ?>" <?php if(isset($route['id'])){ if($GetPurchaseProduct['supplier_id'] == $getSupplier['contact_id']) echo "selected"; } ?>><?php echo $getSupplier['name']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label"><?php echo trans('date_range'); ?></label>
								<div class="input-group">
									<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
									<input class="form-control GetPurchaseReportChange" type="text" name="daterange" id="reportrange" placeholder="<?php echo trans('custom_daterange'); ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered PurchasePaymentReportTableData" data-title="Purchase Payment Report">
						<tfoot>
							<tr class="bg-gray font-17 footer-total text-center">
								<td></td>
								<td><?php echo trans('total_paid_amount'); ?> :</td>
								<td></td>
								<td colspan="3"></td>
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
	[footer]
	<?php
		getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
		getJs('assets/system/js/plugins/fullcalendar/moment.min.js',false);
		getJs('assets/system/js/plugins/daterangepicker/daterangepicker.js');
	?>
	<script>
		$(document).on("click",".purchase_view", function(){
			var purchaseId=$(this).data('purchase-id');
			$(".show_modal").remove(); 
			AS.Http.posthtml({"action" : "PurchaseViewModal","purchase_id":purchaseId}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
		
		$(document).ready(function(){
			function TableDataColums(){
				return [
				{ "title": $_lang.id,"class": "text-center", data : 'transaction_id' },
				{ "title": $_lang.paid_on,"class": "text-center", data : 'paid_date' },
				{ "title": $_lang.paid_amount,"class": "text-center total_paid", data : 'transaction_amount' },
				{ "title": $_lang.supplier_name,"class": "text-center", data : 'pos_contact/name' },
				{ "title": $_lang.payment_method,"class": "text-center", data : 'pos_payment_method/payment_method_name' },
				{ "title": $_lang.purchase_id,"class": "text-center", 
					orderable: false,
					render: function (data, type, row) {
						return '<a href="javascript:void(0)" data-purchase-id="'+row.purchase_id+'" class="purchase_view">'+row.purchase_id+'</a>';
					}
				}  
				];
			}
			
			
			function CustomDateRangeFilter(FilterType) {
				var storeId= $('#store_id').val() || null;
				var supplierId= $('#supplier_id').val() || null;
				var productSearch= $('#product_search').val() || null;
				start = $('input#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
				end = $('input#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
				AS.Http.GetDataTable('.PurchasePaymentReportTableData',TableDataColums(),{ action : "GetPurchasePaymentReportData", between_action : "created_at", from_data : start, to_data : end, date_range : true, product_search : productSearch, supplier_id : supplierId, store_id : storeId},"pos/filter/",FilterType,["total_paid"]);
			}
			
			$(document).on("change",".GetPurchaseReportChange", function(){
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
			
		});
		
	</script>
	[/footer]
	<?php include dirname(__FILE__) .'/include/footer.php';?>		