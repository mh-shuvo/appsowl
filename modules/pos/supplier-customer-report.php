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
[/header]
<div class="row wrapper">
	<div class="col-sm-12"><h3><?php echo trans('supplier_customer_report'); ?></h3></div>
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-content">
				<table class="table table-striped table-bordered SupplierCustomerReport" data-title="Supplier Customer Report">
					<tfoot>
						<tr class="bg-gray font-17 footer-total text-center">
							<td></td>
							<td></td>
							<td></td>
							<td><?php echo trans('total'); ?> :</td>
							<td></td>
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
[footer]
<?php
	getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
	getJs('assets/system/js/plugins/fullcalendar/moment.min.js',false);
	getJs('assets/system/js/plugins/daterangepicker/daterangepicker.js');
?>	
<script type="text/javascript">
	function TableDataColums(){
		return [
		{ "title": $_lang.contact_id,"class": "text-center hidden", data : 'id' },
		{ "title": $_lang.name,"class": "text-center", data : 'name' },
		{ "title": $_lang.contact_id,"class": "text-center", data: 'contact_id',
			orderable: true,
			render: function (data, type, row) {
				if(row.contact_type == 'customer'){
					return '<a href="pos/customer-view/'+row.contact_id+'">'+row.contact_id+'</a>';
					}else{
					return '<a href="pos/supplier-view/'+row.contact_id+'">'+row.contact_id+'</a>';
				}
			}
		},
		{ "title": $_lang.contact_type,"class": "text-center", data : 'contact_type' },
		{ "title": $_lang.total_purchase,"class": "text-center total_purchase", orderable: false, data : 'total_purchase' },
		{ "title": $_lang.total_sales,"class": "text-center total_sales", orderable: false, data : 'total_sales' }
		];
	}
	$(document).ready(function(){
		AS.Http.GetDataTable('.SupplierCustomerReport',TableDataColums(),{ action : "GetSupplierCustomerReportData"},"pos/filter/",false,["total_purchase","total_sales","due"]);
	});
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php';?>		