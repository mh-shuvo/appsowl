<?php defined('_AZ') or die('Restricted access');
	app('pos')->checkPermission('contacts_customer','view',true) or die(redirect("/pos/access-denied"));
		include dirname(__FILE__) .'/include/header.php';
		include dirname(__FILE__) .'/include/side_bar.php';
		include dirname(__FILE__) .'/include/navbar.php';
		$contact_info = app('admin')->getwhereid('pos_contact','contact_id',$this->route['id']);
		$contact_history = app('pos')->GetSalesByCustomerOrder($this->route['id']);
	?>
	[header]
	<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);?>
	<style type="text/css">
		th {
		text-align: center;
		}
		
		.add_btn {
		margin-top: -35px;
		}
		
		.thumbnail img{height:100%; width:100%;}
		
	</style>
	[/header]
	<input id="contact_id" type="hidden" value="<?php echo $this->route['id']; ?>"/>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-4">
			<h2><?php echo trans('contact'); ?></h2>
			<ol class="breadcrumb">
				<li>
					<a href=""><?php echo trans('dashboard'); ?></a>
				</li>
				<li class="active">
					<strong><?php echo trans('contact'); ?></strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="ibox-content  m-b-sm border-bottom">
			<div class="row">
				<div class="col-sm-4">
					<div class="well">
						<label class="control-label"><?php echo trans('id'); ?> :</label>
						<?php echo $this->route['id'];?>
						<br>
						<label class="control-label"><?php echo trans('name'); ?> :</label>
						<?php echo $contact_info['name'];?>
						<br>
						<label class="control-label"><?php echo trans('contact'); ?> :</label>
						<?php echo $contact_info['phone'];?>
						<br>
						<label class="control-label"><?php echo trans('address'); ?> :</label>
						<?php echo $contact_info['address'];?>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="well">
						<label class="control-label"><?php echo trans('total_sales'); ?> :</label>
						<?php if($contact_history['total_sales']!=null){echo number_format($contact_history['total_sales'],2);}else{echo "0.00";} ?>
						<br>
						<label class="control-label"><?php echo trans('paid'); ?> :</label>
						<?php if($contact_history['total_paid']!=null){echo number_format($contact_history['total_paid'],2);}else{echo "0.00";} ?>
					</div>
				</div>
				<?php if(app('admin')->checkAddon('sale_return',true)||app('admin')->checkAddon('due_sale',true)){ ?>
				<div class="col-sm-4">
					<div class="well">
						<?php if(app('admin')->checkAddon('sale_return',true)){ ?>
					    <label class="control-label"><?php echo trans('due'); ?> :</label>
						<?php if($contact_history['total_due']!=null){echo number_format($contact_history['total_due'],2);}else{echo "0.00";} ?>
						<br>
						<?php } if(app('admin')->checkAddon('due_sale',true)){ ?>
						<label class="control-label"><?php echo trans('cash_purchase_return'); ?> :</label>
						<?php if($contact_history['total_return_paid']!=null){echo number_format($contact_history['total_return_paid'],2);}else{echo "0.00";} 
						}
						?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-content">
						
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover customer_details" data-title='Sales Details of <?php echo $contact_info['name'];?>' data-description="<?php echo $contact_info['address']; ?> <?php echo $contact_info['phone'];?>">
								<tfoot>
									<tr class="bg-secondary font-17 footer-total text-center">
										<td colspan="2"></td>
										<td><?php echo trans("total"); ?> :</td>
										<td></td>
										<td></td>
										<th colspan="<?php echo app('admin')->checkAddon('due_sale') ? 2 : 1; ?>"></th>
									</tr>
								</tfoot>
							</table>
						</div>
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
	<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);?>
	<script>
	
		DataTable(false);
		
		function DataTable(FilterType) {
			AS.Http.GetDataTable('.customer_details',TableDataColums(),{ action : "GetCustomerDetailsData", contact_id : "<?php echo $this->route['id']; ?>"},"pos/filter/",FilterType,['sales_total','due']);
		}
		
		$(document).on("click",".sales_view", function(){
			var sales_id = $(this).attr('sales_id');
			$(".show_modal").remove(); 
			AS.Http.posthtml({"action" : "GetCustomerReceiptModal","sales_id":sales_id}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
		
		function TableDataColums(){
			return [
			{ "title": $_lang.date,"class": "text-center", data : 'created_at' },
			{ "title": $_lang.invoice_no,"class": "text-center", data : 'sales_id' },
			{ "title": $_lang.status,"class": "text-center", data : 'sales_status' },
			{ "title": $_lang.total_sale,"class": "text-center sales_total", data : 'sales_total' },
			<?php if(app('admin')->checkAddon('due_sale')){ ?>
			{ "title": $_lang.due,"class": "text-center due", data : 'due' },
			<?php } ?>
			{ "title": $_lang.added_by,"class": "text-center", data : 'user_id' },
			{ "title": $_lang.action,"class": "text-center not-show",
				orderable: false,
				render: function (data, type, row) {
					var html = '<div class="btn-group">'
					+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
					+'<ul class="dropdown-menu pull-right" role="menu">'
					+'<li><a href="javascript:void(0)" sales_id="'+row.sales_id+'" class="sales_view"><i class="fa fa-eye"></i> '+$_lang.view+'</a></li>'
					+'<li><a href="javascript:void(0)" sales_id="'+row.sales_id+'" onclick="GetCustomerReceiptViews(this)"><i class="fa fa-print"></i> '+$_lang.print+'</a></li>'
					<?php if(app('pos')->checkPermission('contacts_customer','delete')){ ?>
						+'<li><a href="javascript:void(0)" sales_id="'+row.sales_id+'" class="sale_delete"><i class="glyphicon glyphicon-trash"></i> '+$_lang.delete+'</a></li>'
					<?php } ?>
					+'</ul>';
					return html;
				}
			}
			];
		}
		
		$('#data_1 .input-group.date').datepicker({
			todayBtn: "linked",
			format: "yyyy-mm-dd",
			keyboardNavigation: false,
			forceParse: false,
			calendarWeeks: true,
			autoclose: true
		});
		
		$(document).on("click",".purchase_view", function(){
			var purchase_id=$(this).attr('purchase_id');
			$(".show_modal").remove(); 
			AS.Http.posthtml({"action" : "PurchaseViewModal","purchase_id":purchase_id}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
		
		$(document).on("click",".confirmed", function(){
			window.location.reload(true);
		});
		
		
		$(document).on("click",".sale_delete", function(){
			var sales_id=$(this).attr('sales_id');
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
								customClass: "confirmed",
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
	<?php include dirname(__FILE__) .'/include/footer.php'; ?>						