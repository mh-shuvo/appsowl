<?php defined('_AZ') or die('Restricted access');
	
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
	app('pos')->checkPermission('purchase_purchase_list','view') or redirect("pos/access-denied");
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
	
	.add_btn {
	margin-top: -35px;
	}
	.purchase-modal {
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
		<div class="ibox">
			<div class="ibox-title">
				<h3><?php echo trans('purchase_list'); ?></h3>
				<?php if(app('admin')->checkAddon('add_purchase')){ ?>
				<a href="/pos/purchase" class="btn btn-primary pull-right add_btn"> <i class="fa fa-plus"></i> <?php echo trans('add_purchase');?></a>
				<?php } ?>
			</div>
			<div class="ibox-content table-responsive">
				
				<table class="table table-striped table-bordered purchase_table zindex-fixed" data-title="Purchase List">
					<tfoot>
						<tr class="bg-secondary font-17 footer-total text-center">
							<td colspan="3"></td>
							<td><?php echo trans("total"); ?> :</td>
							<td></td>
							<?php if(app('admin')->checkAddon('due_purchase')){ ?>
								<td></td>
							<?php } ?>
							
							<?php if(app('admin')->checkAddon('purchase_return')){ ?>
								<td></td>
							<?php } ?>	
							
							<?php if(app('admin')->checkAddon('due_purchase') && app('admin')->checkAddon('purchase_return')){ ?>
								<td></td>
							<?php } ?>
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
<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false); ?>
<script>
	$(document).on("click",".confirm", function(){
		$('.show_modal').modal('hide');
		DataTable(true);
	});
	
	$(document).on("click",".purchase_view", function(){
		var purchase_id=$(this).attr('purchase_id');
		$(".show_modal").remove(); 
		AS.Http.posthtml({"action" : "PurchaseViewModal","purchase_id":purchase_id}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	function TableDataColums(){
		return [
		{ "title": $_lang.serial_number,"class": "text-center", data : 'id' },
		{ "title": $_lang.purchase_id,"class": "text-center", data : 'purchase_id' },
		{ "title": $_lang.supplier_name,"class": "text-center", data : 'pos_contact/name'},
		{ "title": $_lang.status,"class": "text-center", data : 'purchase_status',
			orderable: true,
			render: function (data, type, row) {
				var html='';
				if(row.purchase_status!='received'){
					html+='<select  class="purchase_status_change" id="'+row.purchase_id+'" onchange="PurchaseStatusChange(this);" purchase_id="'+row.purchase_id+'">';
					html+='<option value="received"';
					if('received' == row.purchase_status){
						html+=' selected';
					}
					html+='>Received</option>';
					html+='<option value="ordered"';
					if('ordered' == row.purchase_status){
						html+=' selected';
					}
					html+='>Ordered</option>';
					html+='<option value="pending"';
					if('pending' == row.purchase_status){
						html+=' selected';
					}
					html+='>Pending</option>';
					html+='<select>';
					}else{
					html+='<label class="label label-primary text-uppercase">Received</label>';
				}
				
				$('#'+row.purchase_id).find('option').each(function(i,e){
					if($(e).val() == row.purchase_status){
						$(this).prop('selected', true);
					}
				});
				return html;
			}
		},
		{ "title": $_lang.total_purchase,"class": "text-center purchase_total", data: 'purchase_total' },
		<?php if(app('admin')->checkAddon('due_purchase')){ ?>
			{ "title": $_lang.total_due,"class": "text-center purchase_total", data: 'total_due' },
		<?php } ?>
		<?php if(app('admin')->checkAddon('purchase_return')){ ?>
			{ "title": $_lang.purchase_return,"class": "text-center purchase_total", data: 'total_return' },
		<?php } ?>
		<?php if(app('admin')->checkAddon('due_purchase') && app('admin')->checkAddon('purchase_return')){ ?>
			{ "title": $_lang.total_return_due,"class": "text-center purchase_total", data: 'total_return_due' },
			{ "title": $_lang.total_net_purchase,"class": "text-center purchase_total", data: 'net_purchase' },
		<?php } ?>
		{ "title": $_lang.action,"class": "text-center not-show",
			orderable: false,
			render: function (data, type, row) {
				var html = '<div class="btn-group">'
				+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">'+$_lang.action+'<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
				+'<ul class="dropdown-menu pull-right" role="menu">'
				+'<li><a href="javascript:void(0)" purchase_id="'+row.purchase_id+'" class="purchase_view"><i class="fa fa-eye"></i> '+$_lang.view+'</a></li>'
				<?php if(app('admin')->checkAddon('purchase_return')){ ?>
					+'<li><a href="pos/purchase-return/'+row.purchase_id+'" purchase_id="'+row.purchase_id+'"><i class="fa fa-undo"></i> '+$_lang.return+'</a></li>'				
					<?php } if(app('pos')->checkPermission('purchase_purchase_list','edit')){ ?>
					+'<li><a href="pos/purchase/'+row.purchase_id+'" class="product_edit"><i class="glyphicon glyphicon-edit"></i> '+$_lang.edit+'</a></li>'
				<?php } ?>
				<?php if(app('pos')->checkPermission('purchase_purchase_list','delete')){ ?>
					+'<li><a href="javascript:void(0)" purchase_id="'+row.purchase_id+'" class="purchase_delete"><i class="fa fa-trash"></i> '+$_lang.delete+'</a></li>'
				<?php } ?>
				+'</ul>';
				if(row.purchase_payment_status=='paid'){
					$(".due_clear_li_"+row.purchase_id).addClass('hidden');
				}
				return html;
			}
		}
		
		];
	}
	
	DataTable(false);
	
	function DataTable(FilterType) {
		AS.Http.GetDataTable('.purchase_table',TableDataColums(),{ action : "GetPurchaseListData"},"pos/filter/",FilterType,['purchase_total']);
	}
	
	function PurchaseStatusChange(st){
		var status = $(st).val();
		var purchase_id	= $(st).attr('purchase_id');
		
		jQuery.ajax({
			url: "pos/ajax/",
			data: {
				action: "PurchaseStatusChange",	
				id: purchase_id,
				change_status:status
			},
			type: "POST",
			success:function(data){
				swal({
					title: $_lang.success,
					text: $_lang.purchase_status_updated,
					type: "success",
					confirmButtonColor: "#1ab394", 
				confirmButtonText: $_lang.ok, },
				function (isConfirm) {
					DataTable(true);
				});
				
			}
		});
	}
	
	$(document).on("click",".purchase_delete", function(){
		var purchase_id = $(this).attr('purchase_id');
		swal( {
			title: $_lang.are_you_sure,
			type: "warning", 
			showCancelButton: true, 
			confirmButtonColor: "#DD6B55", 
			confirmButtonText: $_lang.yes, 
			cancelButtonText: $_lang.no, 
			closeOnConfirm: false, 
			closeOnCancel: true,
			},function (isConfirm) {
			if (isConfirm) {
				AS.Http.post({"action" : "DeletePurchase","purchase_id": purchase_id}, "pos/ajax/", function (response) {
						var title_text = $_lang.deleted;
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
					
				});
			}else{DataTable(true);}
		});
	});
	
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php'; ?>																																																			