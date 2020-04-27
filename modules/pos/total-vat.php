<?php defined('_AZ') or die('Restricted access');
		include dirname(__FILE__) .'/include/header.php';
		include dirname(__FILE__) .'/include/side_bar.php';
		include dirname(__FILE__) .'/include/navbar.php';
	?>
	[header]
	<?php 
		getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false); 
		getCss('assets/system/css/plugins/daterangepicker/daterangepicker-bs3.css');
	?>
	<style type="text/css">
		th{text-align: center;}
		.paid_modal{margin-top:-7px}
	</style>
	[/header]
	
	<div class="row wrapper">
		<div class="col-sm-12">
			<h3><?php echo trans('total_vat'); ?></h3>
		</div>
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-title">
						<button class="btn btn-success btn-md pull-right paid_modal"><i class="fa fa-plus-circle"></i>  <?php echo trans('vat_paid');?></button>
			
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered VatTable">
						<tfoot>
							<tr class="bg-gray font-17 footer-total text-center">
								<td colspan="2"></td>
								<td><?php echo trans("total_amount"); ?> :</td>
								<td></td>
								<td colspan="2"></td>
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
						
						<script type="text/javascript">
							$(document).ready(function(){
								
								function TableDataColums(){
									return [
									{ "title": $_lang.vat_id,"class": "text-center hidden", data : 'id'},
									{ "title": $_lang.invoice_no,"class": "text-center", data : 'sales_id'},
									{ "title": $_lang.customer,"class": "text-center", data : 'customer_id'},
									{ "title": $_lang.date,"class": "text-center", data : 'created_at'},
									{ "title": $_lang.amount,"class": "text-center sales_vat", data : 'sales_vat' },
									{ "title": $_lang.status,"class": "text-center", data : 'sales_status' },
									{ "title": $_lang.added_by,"class": "text-center", data : 'user_id' },
									];
								}
								
								AS.Http.GetDataTable('.VatTable',TableDataColums(),{ action : "GetVatData"},"pos/filter/",false,['sales_vat']);
								
							});
							
							$(document).on("click",".paid_modal", function(){
								var transaction_id=$(this).attr('transaction_id');
								$(".show_modal").remove();
								AS.Http.posthtml({"action" : "GetVatPaidModal"}, "pos/modal/", function (data) {
									$(".modal_status").html(data);
									$("#transaction_id").val(transaction_id);
									$(".show_modal").modal("show");
								});
							});
						</script>
						[/footer]
						<?php include dirname(__FILE__) .'/include/footer.php';?>							