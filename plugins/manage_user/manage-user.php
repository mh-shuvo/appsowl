<?php defined('_AZ') or die('Restricted access');
app('pos')->checkPermission('manage_user','view',true) or die(redirect("/pos/access-denied"));
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
?>
[header]
<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);?>
[/header]
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-content">
					<span class="pull-right"><a href="pos/new-user" class="btn btn-primary"><?php echo trans('new_user'); ?></a></span>
					<h2><?php echo trans('registered_user_list'); ?></h2>
					<p>
						<?php echo trans('all_user_need_to_verified'); ?>
					</p>
					<div class="table-responsive">
						<div id="DataTables_Table_0_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
							<table class="table table-striped table-bordered table-hover dataTable user_table" data-title="POS User List"></table>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
[footer]
<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);?>
<script>
	DataTable(false);
	
	function DataTable(FilterType) {
		AS.Http.GetDataTable('.user_table',TableDataColums(),{ action : "GetUserData"},"pos/filter/",FilterType);
	}
	
	function TableDataColums(){
		return [
		{ "title": $_lang.username,"class": "text-center", data : 'username' },
		{ "title": $_lang.email,"class": "text-center", data : 'email' },
		{ "title": $_lang.first_name,"class": "text-center", data : 'first_name' },
		{ "title": $_lang.last_name,"class": "text-center", data : 'last_name' },
		{ "title": $_lang.status,"class": "text-center not-show",
			orderable: false,
			render: function (data, type, row) {
				if(row.banned=='N'){
					return '<button type="button" class="btn btn-primary btn-xs">Active</button>';
					}else{
					return '<button type="button" class="btn btn-danger btn-xs">Deactive</button>';
				}
			}
		},	
			<?php 
					if(app('pos')->checkPermission('manage_user','edit',true)){;
				?>		
		{ "title": $_lang.action,"class": "text-center",
			orderable: false,
			render: function (data, type, row) {
				return '<div class="btn-group">'
				+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
				+'<ul class="dropdown-menu pull-right" role="menu">'
				
				+'<li><a href="pos/new-user/'+row.user_id+'"><i class="glyphicon glyphicon-edit"></i> '+$_lang.edit+'</a></li>'
					
				+'<li><a href="javascript:void(0)" class="change_status" user_id="'+row.user_id+'" status="'+row.banned+'"> '+$_lang.status_change+'</a></li>'
				+'</ul>';
			}
		}
		<?php } ?>
		];
	}
	
	$(document).on("click",".change_status", function(){
		var user_id = $(this).attr('user_id');
		var status = $(this).attr('status');
		AS.Http.post({"action" : "ChangeUserStatus","user_id": user_id,"status": status}, "pos/ajax/", function (response){
			DataTable(true);
		});
	});
</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>