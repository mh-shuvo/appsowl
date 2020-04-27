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
	</style>
	[/header]
	
	<div class="row wrapper">
		<div class="col-sm-12">
			<h2><?php echo trans('total_vat'); ?></h2>
		</div>
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-content">
					<table class="table table-striped table-bordered VatTable">
						<tfoot>
							<tr class="bg-gray font-17 footer-total text-center">
								
								<td><?php echo trans("total_amount"); ?> :</td>
								<td></td>
								<td colspan="4"></td>
							</tr>
						</tfoot>
					</table>
					
				</div>
			</div>
		</div>				
	</div>
	
	[footer]
	<?php
		echo getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
		echo getJs('assets/system/js/plugins/fullcalendar/moment.min.js',false);
		echo getJs('assets/system/js/plugins/daterangepicker/daterangepicker.js');
	?>
	
	<script type="text/javascript">
		$(document).ready(function(){
			
			function TableDataColums(){
				return [
				{ "title": $_lang.date,"class": "text-center", data : 'paid_date' },
				{ "title": $_lang.amount,"class": "text-center transaction_amount", data : 'transaction_amount' },
				{ "title": $_lang.status,"class": "text-center", data : 'transaction_status' },
				{ "title": $_lang.payment_method,"class": "text-center", data : 'payment_method_value' },
				{ "title": $_lang.note,"class": "text-center", data : 'transaction_note' },
				{ "title": $_lang.added_by,"class": "text-center", data : 'user_id' },
				// { "title": $_lang.action,"class": "text-center",
				// 	orderable: false,
				// 	render: function (data, type, row) {
				
				// 		if(row.transaction_status=='due'){
				// 		var html = '<div class="btn-group">'
				// 		+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">'+$_lang.action+'<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
				// 		+'<ul class="dropdown-menu pull-right" role="menu">'
				// 		+'<li><a href="pos/product/'+row.transaction_id+'" data-toggle="modal"><i class="glyphicon glyphicon-edit"></i>'+
				// 		$_lang.paid+'</a></li>'
				// 		+'</ul>';
				// 		}
				// 		else{ html="<p>N/A</p>";}
				// 		return html;
				// 	}
				// }
				
				];
			}
			
			AS.Http.GetDataTable('.VatTable',TableDataColums(),{ action : "GetPaidVatData"},"pos/filter/",false,['transaction_amount']);
			
		});
		
	</script>
	
	[/footer]
	<?php include dirname(__FILE__) .'/include/footer.php';?>	