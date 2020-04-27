<?php defined('_AZ') or die('Restricted access'); 
	
	app('pos')->checkPermission('contacts_customer','view',true) or die(redirect("/pos/access-denied"));
	
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
	
?>
[header]
<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);?>
[/header]
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-4">
		<h2><?php echo trans('customer'); ?></h2>
		<ol class="breadcrumb">
			<li>
				<a href="/pos/home"><?php echo trans('dashboard'); ?></a>
			</li>
			<li class="active">
				<strong><?php echo trans('customer'); ?></strong>
			</li>
		</ol>
	</div>
	<div class="col-lg-8">
		<div class="title-action">
			<a href="javascript:void(0);" class="btn btn-primary btn-sm add_new_contact" type="customer"><i class="fa fa-plus"></i> <?php echo trans('add_customer'); ?></a>
		</div>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover customer_table" data-title='Customer'>
							
							<tfoot>
								<tr class="bg-secondary font-17 footer-total text-center">
									<th colspan="4"></th>
									<th><?php echo trans("total"); ?> :</th>
									<th></th>
									<th></th>
									<th colspan="<?php echo app('admin')->checkAddon('due_sale') ? 3 : 2; ?>"></th>
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
	
	$(document).on("click",".confirmed", function(){
		AS.Http.GetDataTable('.customer_table',TableDataColums(),{ action : "GetCustomerData"},"pos/filter/",true);
		$('.show_modal').modal('hide');
	});
	$(document).on("click",".confirm", function(){
		AS.Http.GetDataTable('.customer_table',TableDataColums(),{ action : "GetCustomerData"},"pos/filter/",true);
		$('.show_modal').modal('hide');
	});
	
	function TableDataColums(){
		return [
		{ "title": $_lang.serial_number,"class": "text-center", data : 'id' },
		{ "title": $_lang.customer_id,"class": "text-center", data : 'contact_id' },
		{ "title": $_lang.name,"class": "text-center", data : 'name' },
		{ "title": $_lang.address,"class": "text-center", data : 'address' },
		{ "title": $_lang.phone,"class": "text-center", data : 'phone' },
		{ "title": $_lang.total_sale,"class": "text-center total_sales", data : 'total_sales',
			orderable: false,
			render:function(data,type,row){
				
				if(row.total_sales == null){
					return 0;
				}
				else{
					return row.total_sales
				}
			} 
			
		},
		<?php if(app('admin')->checkAddon('due_sale')){ ?>
			{ "title": $_lang.total_due,"class": "text-center due_sale", data : 'due_sale' },
		<?php } ?>
		{ "title": $_lang.added_by,"class": "text-center", data : 'user_id' },
		{ "title": $_lang.status,"class": "text-center",
			orderable: false,
			render: function(data, type, row){
				var html = '';
				if(row.contact_status=='active'){
					html ="<label class='label label-primary'>"+row.contact_status+"</label>";
				}
				else{
					html ="<label class='label label-danger'>"+row.contact_status+"</label>";
				}
				return html;
			}
		},
		{ "title": $_lang.action,"class": "text-center not-show",
			orderable: false,
			render: function (data, type, row) {
				var isHide = '';
				var isdelete = '';
				if(row.due_sale<=0){
					isHide = 'hide';
					isdelete = '';
				}
				else{
					isHide = '';
					isdelete = 'hide';
				}
				var html = '<div class="btn-group">'
				+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
				+'<ul class="dropdown-menu pull-right" role="menu">'
				+'<li><a href="pos/customer-view/'+row.contact_id+'"><i class="fa fa-eye"></i> '+$_lang.view+'</a></li>'
				
				<?php if(app('pos')->checkPermission('contacts_customer','edit')){
					if(app('admin')->checkAddon('due_sale')){
					?>
					+'<li><a href="javascript:void(0)" contact_id="'+row.contact_id+'" contact_type="'+row.contact_type+'" due_amount="'+row.total_sale_due+'" class="due_amount '+isHide+'" ><i class="fa fa-undo"></i> '+$_lang.pay_now+'</a></li>'
				<?php } ?>
				+'<li><a href="javascript:void(0);" class="edit_contact" contact_id="'+row.contact_id+'" contact_type="'+row.contact_type+'" pay_terms_type="'+row.pay_terms_type+'" pay_terms_number="'+row.pay_terms_number+'" name="'+row.name+'" business_name="'+row.business_name+'" website_name="'+row.website_name+'" address="'+row.address+'" phone="'+row.phone+'" email ="'+row.email+'"><i class="glyphicon glyphicon-edit"></i> '+$_lang.edit+'</a></li>'
				<?php } if(app('pos')->checkPermission('contacts_customer','delete')){ ?>
				+'<li><a href="javascript:void(0)" name="'+row.name+'" contact_id="'+row.contact_id+'" class="delete_contact_button"><i class="glyphicon glyphicon-trash"></i> '+$_lang.delete+'</a></li>'
				<?php } ?>
				+'</ul>';
				return html;
			}
		}
		];
	}
	
	DataTable(false);
	
	function DataTable(FilterType) {
		AS.Http.GetDataTable('.customer_table',TableDataColums(),{ action : "GetCustomerData"},"pos/filter/",FilterType,['total_sales','due_sale']);
	}
	$(document).on("click",".due_amount", function(){
		var contact_id = $(this).attr('contact_id');
		var contact_type = $(this).attr('contact_type');
		var due_amount = $(this).attr('due_amount');
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetDueModal"}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$("#contact_id").val(contact_id);
			$("#contact_type").val(contact_type);
			$("#due_amount").val(due_amount);
			$("#show_due_amount").html(due_amount);
			$(".show_modal").modal("show");
		});
	});
	$(document).on("click",".add_new_contact", function(){
		var type = $(this).attr('type');
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetNewContact","type" : type}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$("#contact_type").val(type);
			$(".business").hide();
			$(".show_modal").modal("show");
		});
	});
	
	$(document).on("change","#contact_type", function(){
		var type= $("#contact_type").val();
		
		if(type=='customer')
		{
			$(".business").hide();
		}
		else{
			$(".business").show();
		}
	});
	
	$(document).on("click",".edit_contact", function(){
		var contact_id = $(this).attr('contact_id');
		var contact_type = $(this).attr('contact_type');
		var pay_terms_type = $(this).attr('pay_terms_type');
		var pay_terms_number = $(this).attr('pay_terms_number');
		var name = $(this).attr('name');
		var address = $(this).attr('address');
		var phone = $(this).attr('phone');
		var email = $(this).attr('email');
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetNewContact","contact_id" : contact_id, "type" : contact_type}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$("#contact_type").val(contact_type);
			$("#pay_terms_type").val(pay_terms_type);
			$("#pay_terms_number").val(pay_terms_number);
			$("#name").val(name);
			$("#address").val(address);
			$("#phone").val(phone);
			$("#email").val(email);
			// $("#contact_type").attr('disabled','');
			if(contact_type=='customer'){
				$(".business").hide();
				}else{
				$(".business").show();
			}
			$(".show_modal").modal("show");
		});
	});
	
	$(document).on("click",".delete_contact_button", function(){
		var contact_id = $(this).attr('contact_id');
		var name = $(this).attr('name');
		swal( {
			title: $_lang.are_you_sure, 
			text: name, 
			type: "warning", 
			showCancelButton: true, 
			confirmButtonColor: "#DD6B55", 
			confirmButtonText: $_lang.yes, 
			cancelButtonText: $_lang.no, 
			closeOnConfirm: false, 
			closeOnCancel: true
			},function (isConfirm) {
			if (isConfirm) {
				AS.Http.post({"action" : "DeleteContact","contact_id": contact_id,"name":name}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						swal({
							customClass: "confirmed",
							title: $_lang.deleted, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
							},function(isConfirm){
							if(isConfirm){
								DataTable(true);
							}
						});
					}else{ DataTable(true); }
				});
			}else { DataTable(true);}
		})
	});
	
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php'; ?>																