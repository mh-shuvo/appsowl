<?php defined('_AZ') or die('Restricted access');
		include dirname(__FILE__) .'/include/header.php';
		include dirname(__FILE__) .'/include/side_bar.php';
		include dirname(__FILE__) .'/include/navbar.php';
	?>
	[header]
	<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false); ?>
	<style type="text/css">
		.add_btn{margin-top: -42px;}
		th{text-align: center;}
	</style>
	[/header]
	<div class="row wrapper">
		<div class="col-sm-12">
			<h3><strong><?php echo trans('payment_method') ?></strong></h3>
		</div>
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-title">
					<h3><?php echo trans('add_payment_method'); ?></h3>
					<button class="btn btn-success pull-right add_btn"> <i class="fa fa-plus-circle"></i><?php echo trans('add');?></button>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered payment_method_table" data-title='Payment-method'></table>
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
		
		DataTable(false);
		
		function DataTable(FilterType) {
			AS.Http.GetDataTable('.payment_method_table',TableDataColums(),{ action : "GetPaymentMethodData"},"pos/filter/",FilterType);
		}
		
		function TableDataColums(){
			return [
			{ "title": $_lang.payment_method_id,"class": "text-center hidden", data : 'payment_method_id' },
			{ "title": $_lang.payment_method_name,"class": "text-center", data : 'payment_method_name' },
			{ "title": $_lang.account_number,"class": "text-center", data : 'account_number' },
			{ "title": $_lang.minimum_amount,"class": "text-center", data : 'minimum_amount' },
			{ "title": $_lang.status,"class": "text-center not-show",
				orderable: false,
				render: function (data, type, row) {
					if(row.payment_method_status=='active'){
						return '<button type="button" class="btn btn-primary btn-xs">Active</button>';
						}else{
						return '<button type="button" class="btn btn-danger btn-xs">Deactive</button>';
					}
				}
			},
			{ "title": $_lang.added_by,"class": "text-center", data : 'user_id' },
			{ "title": $_lang.created_at,"class": "text-center", data : 'created_at' },
			{ "title": $_lang.updated_at,"class": "text-center", data : 'updated_at' },			
			{ "title": $_lang.action,"class": "text-center",
				orderable: false,
				render: function (data, type, row) {
					var html = '<div class="btn-group">'
					+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">'+$_lang.action+'<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
					+'<ul class="dropdown-menu pull-right" role="menu">'
					+'<li><a href="javascript:void(0)" class="edit_payment_method" payment_method_id="'+row.payment_method_id+'" payment_method_name="'+row.payment_method_name+'" account_number="'+row.account_number+'" minimum_amount="'+row.minimum_amount+'"><i class="glyphicon glyphicon-edit"></i> '+$_lang.edit+'</a></li>'
					+'<li><a href="javascript:void(0)" name="'+row.name+'" payment_method_id="'+row.payment_method_id+'"   class="change_payment_method_status" payment_method_status="'+row.payment_method_status+'"><i class="fa fa-retweet"></i> '+$_lang.status_change+'</a></li>'
					+'<li><a href="javascript:void(0)" name="'+row.name+'" payment_method_id="'+row.payment_method_id+'"   class="delete_payment_method"><i class="glyphicon glyphicon-trash"></i> '+$_lang.delete+'</a></li>'
					+'</ul>';
					return html;
				}
			}
			];
		}
		
		$('.add_btn').click(function(){
			$(".show_modal").remove();
			AS.Http.posthtml({"action" : "GetNewPaymentMethodModal"}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
		
		$(document).on("click",".confirm", function(){
			$('.show_modal').modal('hide');
			DataTable(true);
		});
		
		$(document).on("click",".edit_payment_method", function(){
			var payment_method_id = $(this).attr('payment_method_id');
			var  payment_method_name = $(this).attr('payment_method_name');
			var  account_number = $(this).attr('account_number');
			var  minimum_amount = $(this).attr('minimum_amount');
			$(".show_modal").remove();
			AS.Http.posthtml({"action" : "GetNewPaymentMethodModal","payment_method_id":payment_method_id}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$("#payment_method_name").val(payment_method_name);
				$("#account_number").val(account_number);
				$("#minimum_amount").val(minimum_amount);
				$(".show_modal").modal("show");
			});
		});
		
		$(document).on("click",".change_payment_method_status", function(){
			var payment_method_id = $(this).attr('payment_method_id');
			var payment_method_status = $(this).attr('payment_method_status');
			AS.Http.post({"action" : "ChangePaymentMethodStatus","payment_method_id": payment_method_id,"payment_method_status": payment_method_status}, "pos/ajax/", function (response) {
				if(response.status=='success'){
					location.reload(); 
				}});
		});
		
		$(document).on("click",".delete_payment_method", function(){
			var payment_method_id = $(this).attr('payment_method_id');
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
					AS.Http.post({"action" : "DeletePaymentMethod","payment_method_id": payment_method_id}, "pos/ajax/", function (response) {
						if(response.status=='success'){
							swal({
								title: $_lang.deleted, 
								text: response.message, 
								type: "success",
								confirmButtonColor: "#1ab394", 
								confirmButtonText: $_lang.ok,
							});
						}else{DataTable(true);}
					});
				}else {DataTable(true);}
			})
		});
		
	</script>
	[/footer]
	<?php include dirname(__FILE__) .'/include/footer.php';?>												