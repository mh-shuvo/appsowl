<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/../../modules/pos/include/header.php';
include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
	?>
	[header]
	<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false); ?>
	<style type="text/css">
		th{text-align: center;}
		.add_btn{margin-top: -7px;}
		.product_image img{height: 50px; width: 50px;}
		
		.modal-dialog {
		width: 50%;
		left:25%;
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
			<h3><?php echo trans('list_quotation'); ?></h3>
		</div>
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-title">
					<a href="pos/pos_sales" class="pull-right btn btn-success add_btn"> <i class="fa fa-plus-circle"></i> Add</a>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered list_quotation_table" data-title="Quotation List"></table>
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
		DataTable(false);
		
		function DataTable(FilterType) {
			AS.Http.GetDataTable('.list_quotation_table',TableDataColums(),{ action : "GetListQuotationData"},"pos/filter/",FilterType);
		}
		
		function TableDataColums(){
			return [
			{ "title": $_lang.serial_number,"class": "text-center", data : 'id' },
			{ "title": $_lang.sales_id,"class": "text-center", data : 'sales_id' },
			{ "title": $_lang.customer_id,"class": "text-center", data : 'customer_id' },
			{ "title": $_lang.store_id,"class": "text-center", data : 'store_id' },
			{ "title": $_lang.added_by,"class": "text-center", data : 'added_by' },
			{ "title": $_lang.date,"class": "text-center", data : 'sale_date' },
			{ "title": $_lang.action,"class": "text-center not-show",
				orderable: false,
				render: function (data, type, row) {
					return '<div class="btn-group">'
					+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
					+'<ul class="dropdown-menu pull-right" role="menu">'
					+'<li><a href="javascript:void(0)" sales_id="'+row.sales_id+'" class="quotation_view"><i class="fa fa-eye"></i> '+$_lang.view+'</a></li>'
					+'<li><a href="javascript:void(0)" class="print_invoice" sales_id="'+row.sales_id+'"><i class="fa fa-print"></i> '+$_lang.print+'</a></li>'
					
						+'<li><a href="pos/add_sales/'+row.sales_id+'"><i class="glyphicon glyphicon-edit"></i> '+$_lang.edit+'</a></li>'
						+'<li><a href="javascript:void(0)" sales_id="'+row.sales_id+'" class="delete_sale"><i class="glyphicon glyphicon-trash"></i> '+$_lang.delete+'</a></li>'
					+'</ul>';
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
		
		$(document).on("click",".quotation_view", function(){
			var sales_id = $(this).attr('sales_id');
			$(".show_modal").remove(); 
			AS.Http.posthtml({"action" : "GetQuotationViewModal","sales_id":sales_id}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
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
						}else{ DataTable(true); }
					});
				}else { DataTable(true); }
			})
		});
	</script>
	[/footer]
	<?php
	
	include dirname(__FILE__) .'/../../modules/pos/include/footer.php';
	
	?>				