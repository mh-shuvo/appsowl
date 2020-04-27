<?php defined('_AZ') or die('Restricted access');
	app('admin')->checkAddon('accounts',true);		
	app('pos')->checkPermission('accounts','view',true) or die(redirect("/pos/access-denied"));
		include dirname(__FILE__) .'/../../modules/pos/include/header.php';
		include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
		include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
?>
	[header]
	<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);?>
	<style type="text/css">
		.add_account{margin-top: -42px;}
	</style>
	[/header]
	<div class="wrapper wrapper-content animated fadeInRight">
		
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h2><?php echo trans('account_chart'); ?></h2>
						<?php 
						if(app('pos')->checkPermission('accounts','edit',true)){
						?>
							<a href="javascript:void(0)" class="btn btn-success add_account pull-right"><i class="fa fa-plus-circle"></i> <?php echo trans('add'); ?></a>
						<?php } ?>
					</div>
					<div class="ibox-content">
						
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover account_chart_table" data-title='Expense-category' ></table>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="ModalForm">
		<form>
			<div class="modal_status"></div>
		</form>
	</div>
	[footer]
	<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);?>
	<script>
		
		$(document).on("click",".confirm", function(){
			$('.show_modal').modal('hide');
			DataTable(true);
		});
		
		DataTable(false);
		
		function DataTable(FilterType) {
			AS.Http.GetDataTable('.account_chart_table',TableDataColums(),{ action : "GetAccountChartData"},"pos/filter/",FilterType);
		}
		
		function TableDataColums(){
			return [
			{ "title": $_lang.account_no,"class": "text-center", data : 'chart_no' },
			{ "title": $_lang.account_title,"class": "text-center", data : 'chart_name' },
			{ "title": $_lang.account_type,"class": "text-center", data : 'chart_type' },
			{ "title": $_lang.status,"class": "text-center", 
				orderable: false,
				render: function(data, type, row){
					var html = '';
					if(row.chart_status == 'active'){
						html="<label class='label label-primary'>"+row.chart_status+"</label>"
					}else{
						html="<label class='label label-danger'>"+row.chart_status+"</label>"
					}
					return html;
				}
			},
			{ "title": $_lang.added_by,"class": "text-center", data : 'added_by' },		
			<?php 
				if(app('pos')->checkPermission('accounts','edit',true) or die(redirect("/pos/access-denied"))){
			?>			
			{ "title": $_lang.action,"class": "text-center",
				orderable: false,
				render: function (data, type, row) {
					var html = '<div class="btn-group">'
					+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
					+'<ul class="dropdown-menu pull-right" role="menu">'
					+'<li><a href="javascript:void(0)" class="edit_account_chart" account_id="'+row.id+'" account_no="'+row.chart_no+'" account_title="'+row.chart_name+'" account_type="'+row.chart_type+'"><i class="glyphicon glyphicon-edit"></i> '+$_lang.edit+'</a></li>'
					+'</ul>';
					return html;
				}
			}
			<?php } ?>
			];
		}
		
		$(document).on("click",".add_account", function(){
			// var type = $(this).attr('type');
			$(".show_modal").remove();
			AS.Http.posthtml({"action" : "AddChartAccount"}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
		
		$(document).on("click",".edit_account_chart", function(){
			var account_id = $(this).attr('account_id');
			var account_no = $(this).attr('account_no');
			var account_title = $(this).attr('account_title');
			var account_type = $(this).attr('account_type');
			var account_category_name = $(this).attr('account_category_name');
			$(".show_modal").remove();
			AS.Http.posthtml({"action" : "AddChartAccount","account_id" : account_id}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$("#account_no").val(account_no);
				$("#account_title").val(account_title);
				$("#account_type").val(account_type);
				$(".show_modal").modal("show");
			});
		});
	</script>
	[/footer]
	<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>						