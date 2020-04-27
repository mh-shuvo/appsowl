<?php defined('_AZ') or die('Restricted access'); 
app('admin')->checkAddon('stock_transfer',true);
app('pos')->checkPermission('stock_transfer','view',true) or die(redirect("/pos/access-denied"));
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
	?>
	[header]
	<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false); ?>
	[/header]
	
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-4">
			
			<h2><?php echo str_replace('-', ' ', ucfirst($uri_segments[2])); ?></h2>
			<ol class="breadcrumb">
				<li>
					<a href=""><?php echo trans('dashboard'); ?></a>
				</li>
				<li class="active">
					<strong><?php echo str_replace('-', ' ', ucfirst($uri_segments[2])); ?></strong>
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
							<table class="table table-striped table-bordered table-hover stock_transfer_table" data-title='Stock Transfer' ></table>
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
<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false); ?>
<script>
function TableDataColums(){
return [
{ "title": $_lang.date,"class": "text-center", data : 'created_at' },
{ "title": $_lang.transfer_id,"class": "text-center", data : 'stock_transfer_id' },
{ "title": $_lang.reference_no,"class": "text-center", data : 'reference_no' },
{ "title": $_lang.location_from,"class": "text-center", data : 'from_store' },
{ "title": $_lang.location_to,"class": "text-center", data : 'to_store' },
{ "title": $_lang.shipping_charge,"class": "text-center", data : 'shipping_charge' },
{ "title": $_lang.added_by,"class": "text-center", data : 'added_by' },
{ "title": $_lang.created_at,"class": "text-center", data : 'created_at' },
{ "title": $_lang.action,"class": "text-center not-show",
orderable: false,
render: function (data, type, row) {
return '<div class="btn-group">'
+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
+'<ul class="dropdown-menu pull-right" role="menu">'
+'<li><a href="javascript:void(0)" class="stock_transfer_view" stock_transfer_id="'+row.stock_transfer_id+'" ><i class="fa fa-eye"></i> '+$_lang.view+'</a></li>'
<?php if(app('pos')->checkPermission('stock_transfer','edit',true)){ ?>
+'<li><a href="javascript:void(0)" class="delete_stock_transfer" stock_transfer_id="'+row.stock_transfer_id+'"><i class="glyphicon glyphicon-trash"></i> '+$_lang.delete+'</a></li>'
<?php } ?>
+'</ul>';
}
}
];
}

$(document).ready(function(){
AS.Http.GetDataTable('.stock_transfer_table',TableDataColums(),{ action : "GetStockTransferData"},"pos/filter/");
});

$(document).on("click",".confirm", function(){
$('.show_modal').modal('hide');
AS.Http.GetDataTable('.stock_transfer_table',TableDataColums(),{ action : "GetStockTransferData"},"pos/filter/",true);
});

$(document).on("click",".stock_transfer_view", function(){
var stock_transfer_id = $(this).attr('stock_transfer_id');
$(".show_modal").remove();
AS.Http.posthtml({"action" : "GetStockTransferView","stock_transfer_id" : stock_transfer_id}, "pos/modal/", function (data) {
$(".modal_status").html(data);
$(".show_modal").modal("show");
});
});

$(document).on("click",".delete_stock_transfer", function(){
var stock_transfer_id = $(this).attr('stock_transfer_id');
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
AS.Http.post({"action" : "DeleteStockTransfer","stock_transfer_id": stock_transfer_id}, "pos/ajax/", function (response) {
if(response.status=='success'){
swal({
title: $_lang.deleted, 
text: response.message, 
type: "success",
confirmButtonColor: "#1ab394", 
confirmButtonText: $_lang.ok,
});
}else{
AS.Http.GetDataTable('.stock_transfer_table',TableDataColums(),{ action : "GetStockTransferData"},"pos/filter/",true);
}
});
}else {
AS.Http.GetDataTable('.stock_transfer_table',TableDataColums(),{ action : "GetStockTransferData"},"pos/filter/",true);
}
})
});

</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>		