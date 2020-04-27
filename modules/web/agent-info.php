<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/includes/header.php';
?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="text-center">Agent Information</h1>
				<table class="table table-stripd table-bordered">
					<thead>
					<tr>
						<th class="text-center">SL.</th>
						<th class="text-center">Name</th>
						<th class="text-center">Phone</th>
						<th class="text-center">Zone</th>
						<th class="text-center">Area</th>
						<th class="text-center">Address</th>
						<tr>
					</thead>
					<tbody class="text-center">
					<?php
						$count=1;
						$getAgentInfo=app('admin')->getwhere('as_users','user_role','2');
						foreach($getAgentInfo as $info){
							$data=app('admin')->getwhereid('as_user_details','user_id',$info['user_id']);
					?>
						<tr>
						<td><?php echo $count;?></td>
						<td><?php echo $data['first_name'].' '.$data['last_name'];?></td>
						<td><?php echo $data['phone'];?></td>
						<td><?php echo $data['zone'];?></td>
						<td><?php echo $data['area'];?></td>
						<td><?php echo $data['address'];?></td>
						</tr>
						
					<?php
					$count++;
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

	
<?php include dirname(__FILE__) .'/include/footer.php'; ?> 