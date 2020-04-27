@extends('Layouts.SuperAdminDashboard')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<span class="label label-success pull-right">Today</span>
						<h5>Today Registered User</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$data['today_user']}}</h1>
						
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Total User</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$data['total_user']}}</h1>
						
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Total Active User</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$data['total_active_user']}}</h1>
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Total In Active User</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$data['total_inactive_user']}}</h1>
						
					</div>
				</div>
			</div>
			{{--  <div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Total Current Active User</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">600</h1>
						
					</div>
				</div>
			</div> --}}
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<span class="label label-success pull-right">Today</span>
						<h5>Today Registered Agent</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$data['today_agent']}}</h1>
						
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Total Agent</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$data['total_agent']}}</h1>
						
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Total Active Agent</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$data['total_active_agent']}}</h1>
						
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Total In Active Agent</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$data['total_inactive_agent']}}</h1>
						
					</div>
				</div>
			</div>
			{{-- <div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Total Current Active Agent</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">600</h1>
						
					</div>
				</div>
			</div> --}}
			<!-- @if (strpos(Session::get('admin_data')[0] ['permission'], 'account') !== false)
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Total Bill Created</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$data['total_bill']}}</h1>
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Total UnPaid Bill</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$data['total_unpaid_bill']}}</h1>
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Total Paid Bill</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$data['total_paid_bill']}}</h1>
					</div>
				</div>
			</div>
			@endif -->
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Total Support</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$data['total_support']}}</h1>
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Total Pending Support</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$data['total_pending_support']}}</h1>
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Total Complete Support</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$data['total_complete_support']}}</h1>
					</div>
				</div>
			</div>
			
		</div>
		<div class="row">
			<div class="col-lg-12">
				
			</div>
		</div>
	</div>
</div>
@endsection