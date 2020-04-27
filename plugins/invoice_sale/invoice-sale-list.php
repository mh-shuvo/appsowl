<?php defined('_AZ') or die('Restricted access');
include dirname(__FILE__) .'/../../modules/pos/include/header.php';
include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
?>
[header]
<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false); ?>
<style>
	.add_btn{margin-top: -35px;}
</style>
[/header]
<div class="row wrapper">

	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-title">
			<h3> <?php echo trans('invoice_sale_list'); ?> </h3>
			<?php if(app('admin')->checkAddon('invoice_sale')){?>
			<a href="pos/invoice-sales" class="btn btn-success btn-sm pull-right add_btn"><i class="fa fa-plus"></i> <?php echo trans('add_invoice');?></a>
			<?php }?>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-content table-responsive">
				<table class="table table-striped table-bordered invoice_sale_table" data-title="Invoice Sale List">
					<tfoot>
						<tr class="bg-gray font-17 footer-total text-center">
							<td colspan="2"></td>
							<td><?php echo trans("total"); ?> :</td>
							<td></td>
							<?php if(app('admin')->checkAddon('multiple_payment_method')){ ?>
							<td></td>
							<?php } ?>
							<?php if(app('admin')->checkAddon('due_sale')){ ?>
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
<div class="ModalForm">
	<div class="modal_status"></div>
</div> 
<div class='invoice_receipt_view hidden'></div>
[footer]
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
	
	DataTable(false);
	
	function DataTable(FilterType) {
		AS.Http.GetDataTable('.invoice_sale_table',TableDataColums(),{ action : "GetInvoiceSaleData"},"pos/filter/",FilterType,['sales_subtotal','sales_total','sales_vat','due','paid']);
	}
	
	function TableDataColums(){
		return [
		{ "title": $_lang.serial_number,"class": "text-center", data : 'id' },
		{ "title": $_lang.sales_id,"class": "text-center", data : 'sales_id' },
		{ "title": $_lang.customer_id,"class": "text-center", data : 'customer_id' },
		{ "title": $_lang.added_by,"class": "text-center", orderable: false, data : 'added_by' },
		{ "title": $_lang.total,"class": "text-center sales_total", data : 'sales_total' },
		<?php if(app('admin')->checkAddon('multiple_payment_method')){ ?>
		{ "title": $_lang.paid,"class": "text-center paid", orderable: false, data : 'paid' },
		<?php } ?>
		<?php if(app('admin')->checkAddon('due_sale')){ ?>
		{ "title": $_lang.due,"class": "text-center due", orderable: false, data : 'due' },		
		<?php } ?>
		{ "title": $_lang.payment_status,"class": "text-center",
			orderable: false,
			render: function (data, type, row) {
				var html = '';
				if(row.sales_payment_status == 'paid'){
					html='<label class="label label-primary text-uppercase">'+row.sales_payment_status+'</label>';
				}
				else if(row.sales_payment_status == null){
					html='<label class="label label-default">N/A</label>';
				}
				else{
					html='<label class="label label-danger text-uppercase">'+row.sales_payment_status+'</label>';
				}
				return html;
			}
		},
		{ "title": $_lang.status,"class": "text-center", 
			orderable : false,
			render : function(data, type, row){
				var html = '';
				if(row.sales_status == 'complete'){
					html += '<label class="label label-primary text-uppercase">'+row.sales_status+'</label>';
				}
				else if(row.sales_status == 'cancel'){
					html += '<label class="label label-danger text-uppercase">'+row.sales_status+'</label>';
				}
				else{
					html += '<label class="label label-success text-uppercase">'+row.sales_status+'</label>';
				}
				return html;
			}
		},
		{ "title": $_lang.sale_date,"class": "text-center", data : 'created_at' },
		{ "title": $_lang.action,"class": "text-center not-show",
			orderable: false,
			render: function (data, type, row) {
				return '<div class="btn-group">'
				+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
				+'<ul class="dropdown-menu pull-right" role="menu">'
				
				+'<li><a href="pos/sales-view/'+row.sales_id+'"><i class="fa fa-eye"></i> '+$_lang.view+'</a></li>'
				+'<li><a href="pos/challan/'+row.sales_id+'"><i class="fa fa-eye"></i> '+$_lang.challan+'</a></li>'

				+'<li><a href="pos/pdf/'+row.sales_id+'" class="print_invoice" sales_id="'+row.sales_id+'"><i class="fa fa-print"></i> '+$_lang.print+'</a></li>'
				+'<li class="due_clear_li_'+row.sales_id+'"><a href="javascript:void(0)" sales_id="'+row.sales_id+'" class="due_clear"><i class="fa fa-dollar"></i> '+$_lang.pay+'</a></li>'
				+'<li><a href="pos/invoice-sales/'+row.sales_id+'"><i class="glyphicon glyphicon-edit"></i> '+$_lang.edit+'</a></li>'
				+'<li><a href="javascript:void(0)" sales_id="'+row.sales_id+'" class="delete_sale"><i class="glyphicon glyphicon-trash"></i> '+$_lang.delete+'</a></li>'
				+'</ul>';
				if(row.sales_payment_status == 'paid'){
					$(".due_clear_li_"+row.sales_id).addClass('hidden');
				}
			}
		}
		];
	}
	
	$(document).on("click",".print_invoice", function(){
		var s_id = $(this).attr('sales_id');
		jQuery.ajax({
			url: "pos/ajax/",
			data: {
				action: "GetInvoice",
				id	  : s_id
			},
			type: "POST",
			success:function(data){
				
				$(".invoice_receipt_view").html(data);		
				$.print("#sales_invoice");
				
			},
			error:function (){}
		});
	});
	
	$(document).on("click",".confirm", function(){
		$('.show_modal').modal('hide');
		DataTable(true);
	});
	
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
						swal({
							title: $_lang.due, 
							text: response.message, 
							type: "warning",
							confirmButtonColor: "#f8ac59", 
							confirmButtonText: $_lang.cancel,
						});
					}
				});
			}else { DataTable(true); }
		})
	});
</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>													