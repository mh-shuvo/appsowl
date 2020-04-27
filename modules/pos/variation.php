<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
?>
[header]
<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false); ?>
<style type="text/css">
	.add_variation_btn{margin-top: -42px;}
	th{text-align: center;}
</style>
[/header]
<div class="row">
	<div class="col-sm-12">
		<h2><?php echo trans('variations'); ?></h2>
		
	</div>
	
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-title">
				<h2><?php echo trans('all_variations'); ?></h2>
				<button class="btn btn-success add_variation_btn pull-right"><i class="fa fa-plus-circle"></i> <?php echo trans('add'); ?></button>
			</div>
			<div class="ibox-content">
				<table class="table table-striped table-bordered variation_table" data-title='Variation'></table>
			</div>
		</div>
	</div>
</div>
<!-- VARIATION MODAL -->
<div class="ModalForm">
	<div class="modal_status"></div>
</div> 
[footer]
<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false); ?>
<script>
	
	$(document).ready(function(){
		AS.Http.GetDataTable('.variation_table',TableDataColums(),{ action : "GetVariationData"},"pos/filter/",false);
	});
	
	function TableDataColums(){
		return [
		{ "title": $_lang.serial_number,"class": "text-center", data : 'variation_category_id' },
		{ "title": $_lang.variations,"class": "text-center", data : 'variation_category_name' },
		{ "title": $_lang.variation_value,"class": "text-center", data : 'variation_category_value' },
		{ "title": $_lang.action,"class": "text-center not-show",
			orderable: false,
			render: function (data, type, row) {
				var html = '<div class="btn-group">'
				+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">'+$_lang.action+'<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
				+'<ul class="dropdown-menu pull-right" role="menu">'
				+'<li><a href="javascript:void(0)" class="edit_variation" variation-id="'+row.variation_category_id+'"  variation-name="'+row.variation_category_name+'"  variation-value="'+row.variation_category_value+'"  data-toggle="modal"><i class="glyphicon glyphicon-edit"></i>'+$_lang.edit+'</a></li>'
				+'<li><a href="javascript:void(0)" class="delete_variation"	variation_id="'+row.variation_category_id+'"><i class="fa fa-trash"></i>'+$_lang.delete+'</a></li>'
				+'</ul>';
				return html;
			}
		}
		
		];
	}
	
	$('.add_variation_btn').click(function(){
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetNewVariation"}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	$(document).on("click",".edit_variation", function(){
		var variation_id = $(this).attr('variation-id');
		var  variation_name = $(this).attr('variation-name');
		$(".show_modal").remove(); 
		AS.Http.posthtml({"action" : "GetNewVariation","variation_id" : variation_id}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$("#variation_name").val(variation_name);
			AS.Http.post({"action" : "GetVariationData","select_variation" : variation_id}, "pos/ajax/", function (result) {
				var values = result.values.split(',')
				var i = 1;
				for (var k = 0; k < values.length; k++) {
					if(k==0){
						$("#variation_value").val(values[k]);
						}else{
						edit_modal_add_variation_values_row(values[k]);
					}
					i++;
				}
			});
			$(".show_modal").modal("show");
		});
	});
	
	function edit_modal_add_variation_values_row(v_value){
		var html='<div class="form-group"><div class="row"><div class="col-sm-7 col-sm-offset-3"><input type="text" class="form-control input-sm" name="variation_value[]" placeholder="<?php echo trans("variation_value"); ?>" value="'+v_value+'"></div><div class="col-sm-2"><button type="button" class="btn btn-danger btn-sm" id="remove_variation_values" onclick="remove(this)">-</button></div></div></div>';
		$(".from-body").append(html);
	}
	
	$(document).on("click",".confirm", function(){
		$('.show_modal').modal('hide');
		AS.Http.GetDataTable('.variation_table',TableDataColums(),{ action : "GetVariationData"},"pos/filter/",true);
	});
	
	
	
	$("#edit_variation_values").click(function(){
		var html='<div class="form-group"><div class="row"><div class="col-sm-7 col-sm-offset-3"><input type="text" class="form-control input-sm" name="variation_value[]" placeholder="<?php echo trans("variation_value"); ?>"></div><div class="col-sm-2"><button type="button" class="btn btn-danger btn-sm" id="remove_variation_values" onclick="remove(this)">-</button></div></div></div>';
		$(".from-body").append(html);
	});
	
	
	$(document).on("click",".delete_variation", function(){
		var variation_id = $(this).attr('variation_id');
		swal( {
			title: $_lang.are_you_sure,
			type: "warning", 
			showCancelButton: true, 
			confirmButtonColor: "#DD6B55", 
			confirmButtonText: $_lang.yes, 
			cancelButtonText: $_lang.no, 
			closeOnConfirm: false, 
			closeOnCancel: false
			},function (isConfirm) {
			if (isConfirm) {
				AS.Http.post({"action" : "DeleteVariation","variation_id": variation_id}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						swal({
							title: $_lang.deleted, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
							},function(isConfirm){
							location.reload();
						});
					}
					else{
						location.reload();
					}
				});
			}
			else {
				location.reload();	
			}
		})
	});
	
	function remove(el){
		$(el).parent('div').parent('div').parent('div').remove();
	}
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php';?>				