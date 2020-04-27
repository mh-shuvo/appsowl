<?php defined('_AZ') or die('Restricted access');
		include dirname(__FILE__) .'/include/header.php';
		include dirname(__FILE__) .'/include/side_bar.php';
		include dirname(__FILE__) .'/include/navbar.php';
	?>
	[header]
	<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);?>
	[/header]
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-4">
			
			<h2><?php echo trans('register_report'); ?></h2>
			<ol class="breadcrumb">
				<li>
					<a href="/"><?php echo trans('dashboard'); ?></a>
				</li>
				<li class="active">
					<strong><?php echo trans('register_report'); ?></strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					
					<div class="ibox-content">
						
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover dataTables-example register_table" data-title="Register Report"></table>
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
<?php echo getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);?>
<script>
	
	$(document).on("click",".register_details", function(){
		var register_id = $(this).attr('register_id');
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "RegisterDetails","register_id" : register_id}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	function TableDataColums(){
		return [
		{ "title": $_lang.register_id,"class": "text-center", data : 'register_id' },
		{ "title": $_lang.user_name,"class": "text-center", data : 'user_name' },
		{ "title": $_lang.registry_open_time,"class": "text-center", data : 'register_open' },
		{ "title": $_lang.registry_close_time,"class": "text-center", data : 'register_close' },
		{ "title": $_lang.opening_balance,"class": "text-center", data : 'register_open_balance' },
		{ "title": $_lang.closing_balance,"class": "text-center", data : 'register_close_balance' },			
		{ "title": $_lang.status,"class": "text-center",
			orderable: false,
			render: function (data, type, row) {
				if(row.register_status==='open'){
					return '<button type="button" class="btn btn-primary btn-xs">'+$_lang.open+'</button>';
					}else if(row.register_status==='close'){
					return '<button type="button" class="btn btn-danger btn-xs">'+$_lang.close+'</button>';
				}
			}
		},
		{ "title": $_lang.action,"class": "text-center not-show",
			orderable: false,
			render: function (data, type, row) {
				return '<div class="btn-group">'
				+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
				+'<ul class="dropdown-menu pull-right" role="menu">'
				+'<li><a href="javascript:void(0)" class="register_details" register_id="'+row.register_id+'"><i class="fa fa-eye"></i> '+$_lang.view+'</a></li>'
				+'</ul>';
			}
		}
		];
	}
	
	$(document).ready(function(){
		AS.Http.GetDataTable('.register_table',TableDataColums(),{ action : "GetRegisterData"},"pos/filter/",false,[],[[2, 'desc' ]]);
	});	
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php';
?>