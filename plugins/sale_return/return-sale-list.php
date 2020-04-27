<?php defined('_AZ') or die('Restricted access');
app('pos')->checkPermission('sale_return','view',true) or die(redirect("/pos/access-denied"));
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
	?>
	[header]
	<?php 
		getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
		getCss('assets/system/css/plugins/iCheck/custom.css');
	?>
	<style type="text/css">
		th {
		text-align: center;
		}
	</style>
	[/header]
	
	<div class="row wrapper">
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-title">
					<h4><?php echo trans('sell_return'); ?></h4>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered sale_return_table" data-title="Return Sale List">
					
					<tfoot>
								<tr class="bg-gray font-17 footer-total text-center">
									<td colspan="2"></td>
									<td><?php echo trans("total"); ?> :</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tfoot>
					
					</table>
				</div>
			</div>
		</div>
		
	</div>
	
	
	[footer]
	<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false); ?>
	<script>
		$(document).ready(function() {
			function TableDataColums(){
				return [
				{ "title": $_lang.serial_number,"class": "text-center", data : 'id' },
				{ "title": $_lang.date,"class": "text-center", data : 'created_at' },
				{ "title": $_lang.reference_no,"class": "text-center", data : 'return_id' },
				{ "title": $_lang.customer_name,"class": "text-center", data : 'pos_contact/customer_name' },
				{ "title": $_lang.total_return_amount,"class": "text-center return_total", data : 'return_total' },
				<?php if(app('admin')->checkAddon('multiple_payment_method')){ ?>
				{ "title": $_lang.total_paid,"class": "text-center total_paid", data : 'total_paid' },
				<?php } ?>
				<?php if(app('admin')->checkAddon('due_sale') && app('admin')->checkAddon('sale_return')){ ?>
				{ "title": $_lang.total_due,"class": "text-center total_due", data : 'total_due' },
				<?php } ?>
				
				{ "title": $_lang.action,"class": "text-center not-show",
					orderable: false,
					render: function (data, type, row) {
						var html ='<div class="btn-group">'
						+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
						+'<ul class="dropdown-menu pull-right" role="menu">'
						<?php 
						// if(app('pos')->isPermission($this->currentUser->id,'sale','20') && app('pos')->isPermission($this->currentUser->id,'sale','30')){ 
						?>
						+'<li><a href="pos/sale-return/'+row.sales_id+'"><i class="glyphicon glyphicon-edit"></i> '+$_lang.edit+'</a></li>'
						<?php 
						// }
						?>
						+'<li><a href="javascript:void(0)" class="delete_sale_return"	return_id="'+row.return_id+'"><i class="fa fa-trash"></i>'+$_lang.delete+'</a></li>'
						+'</ul>';
						return html;
					}
				}
				
				];
			}
			AS.Http.GetDataTable('.sale_return_table',TableDataColums(),{ action:"GetSalesReturnData"},'pos/filter/',false,['return_total','total_paid','total_due']);
		});
		$(document).on('click','.delete_sale_return',function(){
			
		});
	</script>
	[/footer]
	<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>	