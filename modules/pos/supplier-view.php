<?php defined('_AZ') or die('Restricted access');
	app('pos')->checkPermission('contacts_supplier','view',true) or die(redirect("/pos/access-denied"));
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
	$contact_info = app('admin')->getwhereid('pos_contact','contact_id',$this->route['id']);
	$contact_history = app('pos')->GetPurchaseByCustomerOrder($this->route['id']);
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
	<input id="contact_id" type="hidden" value="<?php echo $this->route['id']; ?>"/>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-4">
			<h2><?php echo trans('contact'); ?></h2>
			<ol class="breadcrumb">
				<li>
					<a href="/pos/home"><?php echo trans('dashboard'); ?></a>
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
				<div class="col-sm-3">
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
				<div class="col-sm-3">
					<div class="well">
						<label class="control-label"><?php echo trans('business_name'); ?> :</label>
						<?php echo $contact_info['business_name'];?>
						<br>
						<label class="control-label"><?php echo trans('website_name'); ?> :</label>
						<?php echo $contact_info['website_name'];?>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="well">
						<label class="control-label"><?php echo trans('total_transaction'); ?> :</label>
						<?php if($contact_history['total_purchase']!=null){echo number_format($contact_history['total_purchase'],2);}else{echo "0.00";} ?>
						<!-- <br>
						<label class="control-label"><?php echo trans('paid'); ?> :</label>
						<?php if($contact_history['total_paid']!=null){echo number_format($contact_history['total_paid'],2);}else{echo "0.00";} ?>
						<br>
						<label class="control-label"><?php echo trans('return'); ?> :</label>
						<?php if($contact_history['total_return']!=null){echo number_format($contact_history['total_return'],2);}else{echo "0.00";} ?> -->
					</div>
				</div>
				<!-- <div class="col-sm-3">
					<div class="well">
						<label class="control-label"><?php echo trans('return_paid'); ?> :</label>
						<?php if($contact_history['total_return_paid']!=null){echo number_format($contact_history['total_return_paid'],2);}else{echo "0.00";} ?>
					</div>
				</div> -->
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-content">						
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover contact_details" data-title='Supplier-details <?php echo $contact_info['name'];?>' data-description="<?php echo $contact_info['address']; ?> <?php echo $contact_info['phone'];?>">
								<tfoot>
									<tr class="bg-secondary font-17 footer-total text-center">
										<td colspan="4"></td>
										<td><?php echo trans("total"); ?> :</td>
										<td></td>
										<td></td>
										<td></td>
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
	[footer]
	
	<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);?>
	<script>
		
		DataTable(false);
		
		function DataTable(FilterType) {
			
			AS.Http.GetDataTable('.contact_details',TableDataColums(),{ action : "GetContactData", contact_id : "<?php echo $this->route['id']; ?>"},"pos/filter/",FilterType,['payment_due','purchase_total']);
			
		}
		
		function TableDataColums(){
			return [
			{ "title": $_lang.date,"class": "text-center", data : 'purchase_date' },
			{ "title": $_lang.purchase_id,"class": "text-center", data : 'purchase_id' },
			{ "title": $_lang.reference_no,"class": "text-center", data : 'purchase_reference_no' },
			{ "title": $_lang.purchase_status,"class": "text-center", data : 'purchase_status' },
			{ "title": $_lang.payment_status,"class": "text-center", data : 'purchase_payment_status' },
			{ "title": $_lang.grand_total,"class": "text-center purchase_total", data : 'purchase_total' },
			{ "title": $_lang.added_by,"class": "text-center", data : 'user_id' },
			{ "title": $_lang.action,"class": "text-center not-show",
				orderable: false,
				render: function (data, type, row) {
					var html = '<div class="btn-group">'
					+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
					+'<ul class="dropdown-menu pull-right" role="menu">'
					+'<li><a href="javascript:void(0)" purchase_id="'+row.purchase_id+'" class="purchase_view"><i class="fa fa-eye"></i> '+$_lang.view+'</a></li>'
					<?php if(app('pos')->checkPermission('contacts_supplier','edit')){ ?>
						+'<li><a href="pos/purchase/'+row.purchase_id+'" class="btn btn-xs"><i class="glyphicon glyphicon-edit"></i> '+$_lang.edit+'</a></li>'
						<?php } if(app('pos')->checkPermission('contacts_supplier','delete')){ ?>
						+'<li><a href="javascript:void(0)" purchase_id="'+row.purchase_id+'" class="purchase_delete"><i class="glyphicon glyphicon-trash"></i> '+$_lang.delete+'</a></li>'
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
		
		$(document).on("click",".purchase_delete", function(){
			var purchase_id=$(this).attr('purchase_id');
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
					AS.Http.post({"action" : "DeletePurchase","purchase_id": purchase_id}, "pos/ajax/", function (response) {
						if(response.status=='success'){
							swal({
								customClass: "confirmed",
								title: $_lang.deleted, 
								text: response.message, 
								type: "success",
								confirmButtonColor: "#1ab394", 
								confirmButtonText: $_lang.ok,
							});
							}else{
							DataTable(true);
						}
					});
					}else {
					DataTable(true);
				}
			})
		});

		$(document).on("click",".confirmed", function(){
			$('.show_modal').modal('hide');
			DataTable(true);
		});
		
	</script>
	[/footer]
	<?php include dirname(__FILE__) .'/include/footer.php';?>									