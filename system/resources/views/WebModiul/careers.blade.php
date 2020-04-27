@extends('Layouts.SuperAdminDashboard')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-sm-12">
		<div class="ibox float-e-margins">
			<h3>Careeres</h3>
			<div class="row">
				<div class="col-lg-12">
					<div class="tabs-container" wfd-id="87">
						<ul class="nav nav-tabs" wfd-id="92">
							<li class="active" wfd-id="94"><a data-toggle="tab" href="#all" aria-expanded="true">ALL</a></li>
							<li class="" wfd-id="93"><a data-toggle="tab" href="#new" aria-expanded="false">New</a></li>
							<li class="" wfd-id="93"><a data-toggle="tab" href="#selected" aria-expanded="false">Selected</a></li>
							<li class="" wfd-id="93"><a data-toggle="tab" href="#rejected" aria-expanded="false">Rejected</a></li>
						</ul>
						<div class="tab-content" >
							<div id="new" class="tab-pane active" >
								<div class="panel-body">
									<table class="table table-striped table-bordered table-responsive text-center dataTables-example dataTable">
										<thead>
											<tr>
												<th>Sl.No</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone</th>
												<th>Location</th>
												<th>Apply Purpose</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Mehedi Hasan</td>
												<td>mehedi.cse@protonmail.com</td>
												<td>012525252525</td>
												<td>Dhaka</td>
												<td>Web Designer</td>
												<td><span class="label label-primary">New</span></td>
												<td>
													<a class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</a>
												</td>
											</tr>
											<tr>
												<td>1</td>
												<td>Mehedi Hasan</td>
												<td>mehedi.cse@protonmail.com</td>
												<td>012525252525</td>
												<td>Dhaka</td>
												<td>Web Designer</td>
												<td><span class="label label-primary">New</span></td>
												<td>
													<a class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</a>
												</td>
											</tr>
											<tr>
												<td>1</td>
												<td>Mehedi Hasan</td>
												<td>mehedi.cse@protonmail.com</td>
												<td>012525252525</td>
												<td>Dhaka</td>
												<td>Web Designer</td>
												<td><span class="label label-primary">New</span></td>
												<td>
													<a class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div id="active" class="tab-pane" wfd-id="89">
								<div class="panel-body">
									<table class="table table-striped table-bordered table-responsive text-center dataTables-example dataTable">
										<thead>
											<tr>
												<th>Sl.No</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone</th>
												<th>Location</th>
												<th>Apply Purpose</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Mehedi Hasan</td>
												<td>mehedi.cse@protonmail.com</td>
												<td>012525252525</td>
												<td>Dhaka</td>
												<td>Web Designer</td>
												<td><span class="label label-primary">Active</span></td>
												<td>
													<a class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</a>
												</td>
											</tr>
											<tr>
												<td>1</td>
												<td>Mehedi Hasan</td>
												<td>mehedi.cse@protonmail.com</td>
												<td>012525252525</td>
												<td>Dhaka</td>
												<td>Web Designer</td>
												<td><span class="label label-primary">Active</span></td>
												<td>
													<a class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</a>
												</td>
											</tr>
											<tr>
												<td>1</td>
												<td>Mehedi Hasan</td>
												<td>mehedi.cse@protonmail.com</td>
												<td>012525252525</td>
												<td>Dhaka</td>
												<td>Web Designer</td>
												<td><span class="label label-primary">Active</span></td>
												<td>
													<a class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div id="selected" class="tab-pane" wfd-id="89">
								<div class="panel-body">
									<table class="table table-striped table-bordered table-responsive text-center dataTables-example dataTable">
										<thead>
											<tr>
												<th>Sl.No</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone</th>
												<th>Location</th>
												<th>Apply Purpose</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Mehedi Hasan</td>
												<td>mehedi.cse@protonmail.com</td>
												<td>012525252525</td>
												<td>Dhaka</td>
												<td>Web Designer</td>
												<td><span class="label label-primary">Selected</span></td>
												<td>
													<a class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</a>
												</td>
											</tr>
											<tr>
												<td>1</td>
												<td>Mehedi Hasan</td>
												<td>mehedi.cse@protonmail.com</td>
												<td>012525252525</td>
												<td>Dhaka</td>
												<td>Web Designer</td>
												<td><span class="label label-primary">Selected</span></td>
												<td>
													<a class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</a>
												</td>
											</tr>
											<tr>
												<td>1</td>
												<td>Mehedi Hasan</td>
												<td>mehedi.cse@protonmail.com</td>
												<td>012525252525</td>
												<td>Dhaka</td>
												<td>Web Designer</td>
												<td><span class="label label-primary">Selected</span></td>
												<td>
													<a class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div id="rejected" class="tab-pane" wfd-id="89">
								<div class="panel-body">
									<table class="table table-striped table-bordered table-responsive text-center dataTables-example dataTable">
										<thead>
											<tr>
												<th>Sl.No</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone</th>
												<th>Location</th>
												<th>Apply Purpose</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Mehedi Hasan</td>
												<td>mehedi.cse@protonmail.com</td>
												<td>012525252525</td>
												<td>Dhaka</td>
												<td>Web Designer</td>
												<td><span class="label label-danger">Rejected</span></td>
												<td>
													<a class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</a>
												</td>
											</tr>
											<tr>
												<td>1</td>
												<td>Mehedi Hasan</td>
												<td>mehedi.cse@protonmail.com</td>
												<td>012525252525</td>
												<td>Dhaka</td>
												<td>Web Designer</td>
												<td><span class="label label-danger">Rejected</span></td>
												<td>
													<a class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</a>
												</td>
											</tr>
											<tr>
												<td>1</td>
												<td>Mehedi Hasan</td>
												<td>mehedi.cse@protonmail.com</td>
												<td>012525252525</td>
												<td>Dhaka</td>
												<td>Web Designer</td>
												<td><span class="label label-danger">Rejected</span></td>
												<td>
													<a class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							
						</div>
						
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>   
@endsection	