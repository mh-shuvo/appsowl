<?php defined('_AZ') or die('Restricted access'); 
	
	app('pos')->checkPermission('contacts_customer','view',true) or die(redirect("/pos/access-denied"));
	
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
	
?>
[header]
<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);?>

[/header]
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-4">
		<h2><?php echo trans('account_user'); ?></h2>
		<ol class="breadcrumb">
			<li>
				<a href="/pos/home"><?php echo trans('dashboard'); ?></a>
			</li>
			<li class="active">
				<strong><?php echo trans('account_user'); ?></strong>
			</li>
		</ol>
	</div>
	<div class="col-lg-8">
		<div class="title-action">
			<a href="javascript:void(0);" class="btn btn-primary btn-sm add_new_user"><i class="fa fa-plus"></i> <?php echo trans('add_user'); ?></a>
		</div>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover user_table" data-title='Customer'>
							
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
		AS.Http.GetDataTable('.customer_table',TableDataColums(),{ action : "GetAccountUserData"},"pos/filter/",true);
		$('.show_modal').modal('hide');
	});
	$(document).on("click",".confirm", function(){
		AS.Http.GetDataTable('.customer_table',TableDataColums(),{ action : "GetAccountUserData"},"pos/filter/",true);
		$('.show_modal').modal('hide');
	});
	
	function TableDataColums(){
		return [
		{ "title": $_lang.serial_number,"class": "text-center", data : 'id' },
		{ "title": $_lang.user_id,"class": "text-center", data : 'contact_id' },
		{ "title": $_lang.name,"class": "text-center", data : 'name' },
		{ "title": $_lang.address,"class": "text-center", data : 'address' },
		{ "title": $_lang.phone,"class": "text-center", data : 'phone' },
		{ "title": $_lang.total_amount,"class": "text-center",
			orderable: false,
			render: function(data, type, row){
				var html ='';
				html ="<label class='text-info'>"+$_lang.income +" : "+ row.income_amount+"</label></br><label class='text-danger'>"+$_lang.expense +" : "+ +row.expense_amount+"</label>";
				return html;
			}
		},
		{ "title": $_lang.total_paid,"class": "text-center total_paid",
			orderable: false,
			render: function(data, type, row){
				var html ='';
				html ="<label class='text-info'>"+$_lang.income +" : "+ row.income_amount_paid+"</label></br><label class='text-danger'>"+$_lang.expense +" : "+ +row.expense_amount_paid+"</label>";
				return html;
			}
		},
		{ "title": $_lang.total_due,"class": "text-center total_due", 
			orderable: false,
			render: function(data, type, row){
				var html ='';
				html ="<label class='text-info'>"+$_lang.income +" : "+ row.income_amount_due+"</label></br><label class='text-danger'>"+$_lang.expense +" : "+ +row.expense_amount_due+"</label>";
				return html;
			}
		},
		{ "title": $_lang.total_advanced,"class": "text-center total_advanced", 
			orderable: false,
			render: function(data, type, row){
				var html ='';
				html ="<label class='text-info'>"+$_lang.income +" : "+ row.income_amount_advance+"</label></br><label class='text-danger'>"+$_lang.expense +" : "+ +row.expense_amount_advance+"</label>";
				return html;
			}
		},
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
				var html = '<button class="btn btn-primary btn-xs">income</button><button class="btn btn-danger btn-xs">Expense</button><button data-id="'+row.contact_id+'" data-name="'+row.name+'" data-business_name="'+row.business_name+'" data-website="'+row.website_name+'" data-phone="'+row.phone+'" data-email="'+row.email+'" data-address="'+row.address+'" class="btn btn-primary btn-xs EditUser">'+$_lang.edit+'</button><button data-id="'+row.contact_id+'" class="btn btn-danger btn-xs DeleteUser">'+$_lang.delete+'</button>';
				return html;
			}
		}
		];
	}
	
	DataTable(false);
	
	function DataTable(FilterType) {
		AS.Http.GetDataTable('.user_table',TableDataColums(),{ action : "GetAccountUserData"},"pos/filter/",FilterType,['total_sales','due_sale']);
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
	$(document).on("click",".add_new_user", function(){
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetNewAccountUserModal"}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	$(document).on("click",".EditUser",function(){
	    
	   let Id = $(this).data('id'); 
	   let Name = $(this).data('name'); 
	   let Email = $(this).data('email'); 
	   let Phone = $(this).data('phone'); 
	   let Website = $(this).data('website'); 
	   let Business = $(this).data('business_name'); 
	   let Address = $(this).data('address'); 
	    $(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetNewAccountUserModal"}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$("#contact_id").val(Id);
			$("#business_name").val(Business);
			$("#website_name").val(Website);
			$("#address").val(Address);
			$("#email").val(Email);
			$("#name").val(Name);
			$("#phone").val(Phone);
			$("#type").val('account')
			$(".show_modal").modal("show");
		});
		
	});
	
	$(document).on("click",".DeleteUser", function(){
		var contact_id =$(this).data('id'); 
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
				AS.Http.post({"action" : "DeleteAccountUser","contact_id": contact_id}, "pos/ajax/", function (response) {
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
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>																