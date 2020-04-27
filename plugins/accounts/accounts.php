<?php defined('_AZ') or die('Restricted access');
	app('admin')->checkAddon('accounts',true);		
	app('pos')->checkPermission('accounts','view',true) or die(redirect("/pos/access-denied"));
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';		
?>
[header]
<?php 
	getCss('assets/system/css/plugins/daterangepicker/daterangepicker-bs3.css');
	getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
?>
<style type="text/css">
	.AddNewAccount{margin-top: -42px;}
</style>
[/header]
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="ibox-title">
		<h5> <?php echo trans("manage_head_of_account");?> </h5>
		<button class="btn btn-primary btn-sm pull-right AddNewHeadOfAccount m-t-n-sm"><?php echo trans("add_bank");?></button>
	</div>
	<div class="ibox-content  m-b-sm border-bottom table-responsive">
		<div class="row">
			<?php
				if(app('admin')->checkAddon('multiple_store_warehouse')){
			?>
			<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label">
						<?php echo trans('business_location'); ?>
					</label>
					<select class="form-control get_expense_list_filter" id="store_id" name="store_id">
						<option value="0">All</option>
						<?php $posStores = app('admin')->getall('pos_store'); foreach($posStores as $posStore){	?>
							<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore[ 'store_id']==$this->currentUser->store_id) echo "selected"; ?>>
								<?php echo $posStore['store_name']; ?>
							</option>
						<?php } ?>
					</select>
				</div>
			</div>
				<?php } ?>
		</div>
		<table class="table table-striped table-bordered text-center AccountHeadTable"></table>
	</div>
</div>
<div class="ModalForm">
	<div class="modal_status"></div>
</div>
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php';?>		
[footer]
<?php 
	getJs("assets/system/js/plugins/dataTables/datatables.min.js",false);
	getJs('assets/system/js/plugins/fullcalendar/moment.min.js',false);
	getJs('assets/system/js/plugins/daterangepicker/daterangepicker.js');
?>

<script type="text/javascript">
	
	$(document).on("click",".AddNewHeadOfAccount", function(){
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "AddNewHeadOfAccount"}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	$(document).on('click','.DeletePaymentMethod',function(){
		
	})
	$(document).on("click",".DeletePaymentMethod", function(){
		var payment_method_id = $(this).data('id');
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
						GetTableData(true);
					}else{GetTableData(true);}
				});
			}else {GetTableData(true);}
		})
	});
	
</script>

<script>
	$(document).ready(function(){
		$(document).on("change",".get_expense_list_filter", function(){
			GetTableData(true);
		});
		
		function TableDataColums(){
			return [
			{ "title": $_lang.account,"class": "text-center", data : 'payment_method_name' },
			{ "title": $_lang.balance,"class": "text-center", data : 'current_balance' , },
			{ "title": $_lang.status,"class": "text-center", data : 'payment_method_status' },
			{ "title": $_lang.action,"class": "text-center",
				orderable: false,
				render: function(data, type, row){
					var html ='';
					html = '<button class="btn btn-danger btn-sm DeletePaymentMethod" data-id = "'+row.payment_method_id+'" type="button"><i class="fa fa-trash"></i> '+$_lang.delete+'</button>';
					return html;
				}
			},
			];
		}	
		$(document).ready(function(){	
			GetTableData(false);
		});
		
		function GetTableData(Filtertype)
		{
			var businessLocation= $('#store_id').val() || 0;
			AS.Http.GetDataTable('.AccountHeadTable',TableDataColums(),{ action : "GetHeadOfAccountData", store_id : businessLocation},"pos/filter/",Filtertype);
		}
		
	});
	
</script>

[/footer]			