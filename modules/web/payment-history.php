<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	echo getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
	// include dirname(__FILE__) .'/include/navbar.php';
	$payment_history=app('admin')->getwhere('as_invoices','user_id',ASSession::get("user_id"));
?>

<style>
	th{text-align:center;}
</style>
<section class="gray-section">
	<div class="ibox">
		<div class="ibo x-title">
			<h3><?php echo trans('payment_history');?></h3>
		</div>
		<div class="ibox-content">
			<table class="table table-striped table-bordered dataTables-example">
				<thead>
					<tr>
						<th><?php echo trans('serial_no');?></th>
						<th><?php echo trans('title');?></th>
						<th><?php echo trans('payment_for');?></th>
						<th><?php echo trans('subscribe_id');?></th>
						<th><?php echo trans('subscribe_start_date');?></th>
						<th><?php echo trans('subscribe_end_date');?></th>
						<th><?php echo trans('amount');?></th>
						<th><?php echo trans('payment_date');?></th>
						<th><?php echo trans('status');?></th>
					</tr>
				</thead>
				<tbody class="text-center">
					<?php
						$count=1;
						$title='';
						foreach($payment_history as $payment){
							$subscribe=app('admin')->getwhereid('as_subscribe','subscribe_id',$payment['subscribe_id']);
							$software=app('admin')->getwhereid('as_software','software_id',$subscribe['software_id']);
							$software_variation=app('admin')->getwhereid('as_software_variation','software_variation_id',$subscribe['software_variation_id']);
							if($subscribe['subscribe_type']=='software'){
								$software_variaton=app('admin')->getwhereid('as_software_variation','software_variation_id',$subscribe['software_variation_id']);
								$software=app('admin')->getwhereid('as_software','software_id',$subscribe['software_id']);
								$title=$software['software_title'].'['.$software_variaton['software_variation_name'].']';
							}
							else if($subscribe['subscribe_type']=='plugins'){
								$plugins=app('admin')->getwhereid('as_plugins','plugins_id',$subscribe['plugins_id']);
								$title=$plugins['plugins_name'];
							}
							else{
								$service=app('admin')->getwhereid('as_services','service_id',$subscribe['service_id']);
								$title = $service['service_name'];
							}
						?>
						<tr>
							<td><?php echo $count;?></td>
							<td><?php echo $title;?></td>
							<td><?php if($payment['invoice_details']) { echo $payment['invoice_details']; }else{ echo "Software Subscribe Payment"; } ?></td>
							<td><?php echo $subscribe['subscribe_id'];?></td>
							<td><?php echo getdatetime($payment['subscribe_start_date'],3);?></td>
							<td><?php echo getdatetime($payment['subscribe_end_date'],3);?></td>
							<td><?php echo $payment['invoice_amount'];?></td>
							<td><?php echo getdatetime($payment['created_at'],3);?></td>
							<td>
								<?php if($payment['invoice_status']=='paid'){ ?>
									<label class="label label-success"><?php echo ucwords($payment['invoice_status']); ?></label>
									<?php }else if($payment['invoice_status']=='hold'){ ?>
									<label class="label label-warning"><?php echo ucwords($payment['invoice_status']); ?></label>
									<?php }else{ ?>
									<label class="label label-danger"><?php echo ucwords($payment['invoice_status']); ?></label>
								<?php } ?>
							</td>
						</tr>
					<?php $count++; } ?>
				</tbody>
				
			</table>
		</div>
		
	</div>
</section>
[footer]
<?php echo getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false); ?>
<script>
	$(document).ready(function(){
		$('.dataTables-example').DataTable({
			pageLength: 25,
			responsive: true,
			dom: '<"html5buttons"B>lTfgitp',
			buttons: [
			{extend: 'excel', title: 'purchase_report'},
			{extend: 'pdf', title: 'purchase_report'},
			
			{extend: 'print',
				customize: function (win){
					$(win.document.body).addClass('white-bg');
					$(win.document.body).css('font-size', '10px');
					
					$(win.document.body).find('table')
					.addClass('compact')
					.css('font-size', 'inherit');
				}
			}
			]
			
		});
	});
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php'; ?> 