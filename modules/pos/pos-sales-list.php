<?php defined('_AZ') or die('Restricted access');
	app('pos')->checkPermission('sale_pos_sale_list','view',true) or die(redirect("/pos/access-denied"));
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
?>
[header]
<?php 
	getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
	getCss('assets/system/css/plugins/iCheck/custom.css');
?>
<style type="text/css">
	th {
	text-align: center;
	}
	.add_pos_sale{margin-top: -40px;
</style>
[/header]
<div class="row wrapper">
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-title">
				<h3><?php echo trans("pos_sell_list"); ?></h3>
				<a href="pos/pos-sales" class="btn btn-success pull-right add_pos_sale"><i class="fa fa-plus"></i> <?php echo trans('pos_terminal');?></a>
			</div>
			<div class="ibox-content">
				<div class="table-responsive">
					<table class="table table-striped table-bordered pos_sales_list" data-title="<?php echo trans("sale_list"); ?>">
						<tfoot>
							<tr class="bg-gray font-17 footer-total text-center">
								<td colspan="3"></td>
								<td><?php echo trans("total"); ?> :</td>
								<td></td>
								<?php if(app('admin')->checkAddon('multiple_payment_method')){ ?>
									<td></td>
								<?php } ?>
								<?php if(app('admin')->checkAddon('due_sale')){ ?>
									<td></td>
								<?php } ?>
								<?php if(app('admin')->checkAddon('sale_return')){ ?>
									<td></td>
								<?php } ?>
								<td colspan="4"></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="ModalForm">
	<div class="modal_status"></div>
</div>  
<div class='last_receipt_view hidden'></div>

[footer]
</script>
<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);?>
<script>
	
	$(document).on("click",".due_clear", function(){
		var sales_id = $(this).attr('sales_id');
		$(".show_modal").remove(); 
		AS.Http.posthtml({"action" : "PurchaseDueClear","sales_id":sales_id}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	$(document).on("click",".sales_view", function(){
		var sales_id = $(this).attr('sales_id');
		$(".show_modal").remove(); 
		AS.Http.posthtml({"action" : "GetSalesViewModal","sales_id":sales_id}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	function TableDataColums(){
		return [
		{ "title": $_lang.date,"class": "text-center", data : 'created_at'},
		{ "title": $_lang.receipt_no,"class": "text-center", data : 'sales_id' },
		{ "title": $_lang.customer_name,"class": "text-center", data : 'customer_id',
			orderable:true,
			render:function(data,type,row){
				if(row.customer_id == null){
					return 'Walk in Customer';
				}
				else{
					return row.customer_id;
				}
			}
		},
		{ "title": $_lang.sales_status,"class": "text-center",
			orderable: false,
			render: function (data, type, row) {
				return "<label class='label label-primary text-uppercase'>"+row.sales_status+"</label>";
			}
		},
		{ "title": $_lang.total_sale,"class": "text-center sales_total", data : 'sales_total' },
		{ "title": $_lang.total_sale_without_vat,"class": "text-center sales_total", data : 'sales_vat' },
		<?php if(app('admin')->checkAddon('multiple_payment_method')){ ?>
			{ "title": $_lang.total_paid,"class": "text-center total_paid", orderable: false, data : 'total_paid' },
		<?php } ?>
		<?php if(app('admin')->checkAddon('due_sale')){ ?>
			{ "title": $_lang.total_due,"class": "text-center total_due", orderable: false, data : 'total_due' },
		<?php } ?>
		<?php if(app('admin')->checkAddon('sale_return')){ ?>
			{ "title": $_lang.sale_return,"class": "text-center total_return", orderable: false, data : 'total_return' },
		<?php } ?>
		{ "title": $_lang.sales_by,"class": "text-center", orderable: false,  data : 'user_id' },
		{ "title": $_lang.payment_status,"class": "text-center", orderable: false,  data : 'sales_payment_status' },
		{ "title": $_lang.action,"class": "text-center not-show",
			orderable: false,
			render: function (data, type, row) {
				var html = '<div class="btn-group">'
				+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
				+'<ul class="dropdown-menu pull-right" role="menu">'
				+'<li><a href="javascript:void(0)" sales_id="'+row.sales_id+'" class="sales_view" ><i class="fa fa-eye"></i> '+$_lang.view+'</a></li>'
				+'<li><a href="javascript:void(0)" sales_id="'+row.sales_id+'" onclick="GetCustomerReceiptViews(this)"><i class="glyphicon glyphicon-print"></i> '+$_lang.print+'</a></li>'
				<?php if(app('pos')->checkPermission('sale_pos_sale_list','edit')){
					if(app('admin')->checkAddon('sale_return')){
					?>
					+'<li><a href="pos/sale-return/'+row.sales_id+'"><i class="fa fa-undo"></i> '+$_lang.return+'</a></li>'
				<?php } ?>
				+'<li><a href="pos/pos-sales/'+row.sales_id+'"><i class="glyphicon glyphicon-edit"></i> '+$_lang.edit+'</a></li>'
				<?php } if(app('pos')->checkPermission('sale_pos_sale_list','delete')){ ?>
				+'<li><a href="javascript:void(0)" sales_id="'+row.sales_id+'" class="delete_sale"><i class="glyphicon glyphicon-trash"></i> '+$_lang.delete+'</a></li>'
				<?php } ?>
				+'</ul>';
				if(row.sales_payment_status=='paid'){
					$(".due_clear_li_"+row.sales_id).addClass('hidden');
				}
				return html;
			}
		}
		
		];
	}
	
	$(document).on("click",".confirm", function(){
		$('.show_modal').modal('hide');
		DataTable(true);
	});
	
	DataTable(false);
	
	function DataTable(FilterType) {
		var FilterColumn=['sales_total','total_paid','total_due','total_change'];  
		<?php if(app('admin')->checkAddon('sale_return')){ ?>
			FilterColumn.push('total_return');
		<?php } ?>
		AS.Http.GetDataTable('.pos_sales_list',TableDataColums(),{ action : "GetPosSaleList"},"pos/filter/",FilterType,FilterColumn);
	}
	
	function StatusChange(st){
		var status = $(st).val();
		var sale_id	= $(st).attr('sales_id');
		
		jQuery.ajax({
			url: "pos/ajax/",
			data: {
				action: "SalesStatusChange",	
				id: sale_id,
				change_status:status
			},
			type: "POST",
			success:function(data){
				swal({
					title: $_lang.success,
					text: $_lang.sales_status_updated,
					type: "success",
					confirmButtonColor: "#1ab394", 
				confirmButtonText: $_lang.ok, },
				function (isConfirm) {
					DataTable(true);
				});
			}
		});
	}
	
	$(document).on("click",".delete_sale", function(){
		var sales_id = $(this).attr('sales_id');
		swal( {
			title: $_lang.are_you_sure,
			type: "warning", 
			showCancelButton: true, 
			confirmButtonColor: "#DD6B55", 
			confirmButtonText: $_lang.yes, 
			cancelButtonText: $_lang.no, 
			closeOnConfirm: false, 
			closeOnCancel: true
			},function (isConfirm) {
			if (isConfirm) {
				AS.Http.post({"action" : "DeleteSale","sales_id": sales_id}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						swal({
							title: $_lang.deleted, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
						});
					}else{ 
					var title_text = $_lang.due;
					if(response.status=='error'){
					title_text = $_lang.error;
					}
					swal({
					title: title_text,
					text: response.message,
					type: response.status,
					confirmButtonColor: "#1ab394", 
					confirmButtonText: $_lang.ok, },
					function (isConfirm) {
					DataTable(true);
					});
					}
					});
					}else { DataTable(true); }
					})
					});
					
					</script>
					[/footer]
					<?php include dirname(__FILE__) .'/include/footer.php'; ?>																				