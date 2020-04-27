<?php defined('_AZ') or die('Restricted access'); 
	
	app('pos')->checkPermission('products_product_list','view') or redirect("pos/access-denied");
	
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
	if(isset($this->route['id'])){
		$GetProduct = app('admin')->getwhereid("pos_product","product_id",$this->route['id']);
	}
	$productId = 'PR'.gettoken(8);
?>
[header]
<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false); ?>
<style type="text/css">
	th{text-align: center;}
	.product_image img{height: 50px; width: 50px;}
	.full-width-modal-dialog {
	width: 85%;
	left:8%;
	height: auto;
	margin: 0;
	padding: 0;
	}
	
	.full-width-modal-content {
	height: auto;
	min-height: 100%;
	border-radius: 0;
	}
	.getVariation{cursor:pointer;}
	.thumbnail img{height:100%; width:100%;}
	.add_btn{margin-top: -35px;}
</style>
[/header]

<div class="row">
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-title">
			<h3> <?php echo trans('products'); ?> </h3>
			<a href="pos/product" class="btn btn-success btn-sm pull-right add_btn"><i class="fa fa-plus"></i> <?php echo trans('ad_product');?></a>
			</div>
		</div>
	</div>
</div>	
<div class="row">
	<div class="col-sm-12">
		<div class="ibox">
			
			<div class="ibox-content table-responsive">
				<table class="table table-striped table-bordered" id="product_table" data-title="Product List"></table>
			</div>
		</div>
	</div>
</div>
<div class="ModalForm">
	<div class="modal_status"></div>
</div>
<div id="barcode_view" class="hidden"></div>
[footer]
<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false); ?>
<script type="text/javascript">
	
	function TableDataColums(){
		return [
		{ "title": $_lang.serial_number,"class": "text-center", data : 'id' },
		{ "title": $_lang.image,"class": "text-center not-show", orderable: false,
			render: function (data, type, row) {
				var html = '<img class="img-rounded img-sm" src="'+row.product_image+'" alt="Product image">';
				return html;
			}
		},
		{ "title": $_lang.product_id,"class": "text-center", data : 'product_id' },
		{ "title": $_lang.product_name,"class": "text-center",data : 'product_name',
			orderable: true,
			render: function (data, type, row) {
				if(row.product_featured == "true"){
					var html = "<i class='fas fa-medal' style='color:#1ab394;'></i> "+row.product_name;
					}else{
					var html = row.product_name;
				}
				return html;
			}
		},
		{ "title": $_lang.type,"class": "text-center",  orderable: false,
			render: function (data, type, row) {
				if(row.product_type == "single"){
					var html = "<label class='label label-success' product-id='"+row.product_id+"'>"+row.product_type.toUpperCase()+"</label>";
					}else{
					var html = "<div class='tooltip-demo'><label data-toggle='tooltip' data-placement='left' title='"+$_lang.click_here_to_show_products_with_variation+"' class='label label-primary getVariation' product-id='"+row.product_id+"' product-name='"+row.product_name+"'>"+row.product_type.toUpperCase()+"</label><div>";
				}
				return html;
			}
		},
		{ "title": $_lang.vat, "class": "text-center", orderable: false,
			render: function (data, type, row) {
				if(row.product_vat==null || row.product_vat==''){
					row.product_vat=0;
				}
				if(row.product_vat_type == "percent"){
					var html = row.product_vat + '%';
					}else{
					var html = row.product_vat + 'Tk';
				}
				return html;
			}
		},
		{ "title": $_lang.category,"class": "text-center", data : 'pos_category/category_name' },
		{ "title": $_lang.unit,"class": "text-center", data : 'pos_unit/unit_name' },
		{ "title": $_lang.brand,"class": "text-center", data : 'pos_brands/brand_name' },
		{ "title": $_lang.added_by,"class": "text-center not-show", data : 'user_id' },
		{ "title": $_lang.date_and_time, "class": "text-center", data : 'created_at'},
		{ "title": $_lang.action,"class": "text-center not-show",
			orderable: false,
			render: function (data, type, row) {
				
				if(row.product_featured == "false"){
					var featured_html='<a href="javascript:void(0)" product_id="'+row.product_id+'" featured_value="'+row.product_featured+'" class="featured_change" ><i class="fas fa-medal"></i> '+$_lang.featured+'</a>'
				}
				else{
					var featured_html='<a href="javascript:void(0)" product_id="'+row.product_id+'" featured_value="'+row.product_featured+'" class="featured_change" ><i class="fab fa-strava"></i> '+$_lang.unfeatured+'</a>'
				}
				if(row.exists!='exists'){
					var product_exists = '<li><a href="javascript:void(0)" class="delete_product" product_id="'+row.product_id+'"><i class="glyphicon glyphicon-trash"></i> '+$_lang.delete+'</a></li>';
				}
				else{
					var product_exists = '';
				}
				
				var html = '<div class="btn-group">'
				+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">'+$_lang.action+'<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
				+'<ul class="dropdown-menu pull-right" role="menu">'
				+'<li><a href="javascript:void(0)" product_id="'+row.product_id+'" class="product_view" ><i class="fa fa-eye"></i> '+$_lang.view_barcode+'</a></li>'
				<?php if(app('pos')->checkPermission('products_product_list','edit')){ ?>
					+'<li><a href="pos/product/'+row.product_id+'" data-toggle="modal"><i class="glyphicon glyphicon-edit"></i> '+$_lang.edit+'</a></li>'
				<?php } ?>
				<?php if(app('pos')->checkPermission('products_product_list','edit')){?>
					+product_exists
				<?php } ?>
				+'<li>'+featured_html+'</li>'
				+'<li><a href="javascript:void(0)" class="delete_product" product_id="'+row.product_id+'"><i class="glyphicon glyphicon-trash"></i> '+$_lang.delete+'</a></li>'
				+'</ul></div>';
				return html;
			}
		}
		
		];
	}	
	$(document).ready(function(){	
		GetTableData(false);
	});
	function GetTableData(Filtertype)
	{
		AS.Http.GetDataTable('#product_table',TableDataColums(),{ action : "GetProductListData"},"pos/filter/",Filtertype);
	}
	
	$(document).on("click",".product_view", function(){
		var product_id = $(this).attr('product_id');
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetProductView","product_id" : product_id}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	$(document).on("click",".getVariation", function(){
		var product_id = $(this).attr('product-id');
		var product_name = $(this).attr('product-name');
		// alert(product_id);
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetProductViewByVariation","product_id" : product_id,"product_name" : product_name}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	$(document).on("click",".featured_change", function(){
		var p_id = $(this).attr('product_id');
		var value = $(this).attr('featured_value');
		jQuery.ajax({
			url:"pos/ajax/",
			data:{
				action			: "ProductFeaturedChange",
				product_id		: p_id,
				featured_value	: value
			},
			success:function(res){
				if(res.status=='success'){
					GetTableData(true);
				}
			}
		});
	});
	$(document).on("click",".delete_product", function(){
		var p_id = $(this).attr('product_id');
		swal({
		title: $_lang.are_you_sure,
		text: "",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: $_lang.yes,/*"Yes!"*/
		cancelButtonText:$_lang.no ,/*"No!"*/
		closeOnConfirm: true,
		closeOnCancel: false },
		function (isConfirm) {
			if (isConfirm) {
				jQuery.ajax({
					url:"pos/ajax/",
					data:{
						action			: "ProductDelete",
						product_id		: p_id
					},
					success:function(res){
						if(res.status=='success'){
							GetTableData(true);
						}
					}
				});
				
				} else {
				swal(
				/*$_lang.cancelled, "", "error"*/
				{
					title: $_lang.cancelled,
					text: "",
					type: "warning",
					confirmButtonColor: "#DD6B55",
					confirmButtonText: $_lang.ok,/*"Yes!"*/
				}
				); /*"বাতিল করা হয়েছে"*/
			}
		});
	});
	
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php'; ?>			