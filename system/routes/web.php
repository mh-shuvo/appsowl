<?php
	
	Route::get('/test', 'HomeController@test');
	Route::post('/test-submit', 'HomeController@test_submit');
	/*Front End Web*/
	
	// Route::get('/', 'HomeController@login');
	Route::get('/', 'HomeController@login');
	Route::get('/login', 'HomeController@login');
	Route::post('/login', 'HomeController@login_submit');
	
	Route::get('/email-varify/{token}', 'HomeController@email_varification')->name('email-varify');
	
	/*Common Route*/
	
	Route::post('/logout', 'HomeController@logout');
	Route::get('/home', 'HomeController@dashboard');
	Route::post('/change-password', 'HomeController@change_password');
	
	/*Download*/
	
	Route::get('/download-file/{filename}/{for}', 'HomeController@download_file');
	
	/*Datatable*/
	
	Route::get('/datatable/{table}/{user_id?}', 'DataTableController@datatable');
	
	// Support //
	
	Route::get('/ticket-manage','SupportController@index');
	Route::get('/support/{id}', 'SupportController@user_support_list');
	
	Route::post('/ticket-submit', 'SupportController@ticket_submit');
	Route::get('/ticket-details/{ticket_no}','SupportController@ticket_details');
	Route::post('/ticket-replay', 'SupportController@ticket_replay');
	/*Agent Admin Dashboard*/
	
	// Route::get('/AgentAdminDashboard', 'AgentAdminController@dashboard');
	// Route::get('/AgentAdminApplication', 'AgentAdminController@application');
	// Route::get('/AgentAdminProfile', 'AgentAdminController@profile');
	// Route::get('/AgentAdminUserInfo', 'AgentAdminController@user_info');
	// Route::get('/AgentAdminPaymentReceive', 'AgentAdminController@receive_payment');
	// Route::get('/AgentAdminPaymentWithdrawal', 'AgentAdminController@withdrawal_payment');
	// Route::get('/AgentAdminCreateUser', 'AgentAdminController@create_user');
	// Route::get('/AgentAdminSupport', 'AgentAdminController@support');
	// Route::get('/AgentAdminSubscriptionHistory', 'AgentAdminController@history');
	// Route::get('/AgentAdminUserPaymentHistory', 'AgentAdminController@user_payment_history');
	// /*User Admin Dashboard*/
	// Route::get('/UserAdminDashboard', 'UserAdminController@dashboard');
	// Route::get('/UserAdminProfile', 'UserAdminController@profile');
	// Route::get('/UserAdminSubUser', 'UserAdminController@sub_user');
	// Route::get('/UserAdminPaymentBill', 'UserAdminController@payment_bill');
	// Route::get('/UserAdminApplication', 'UserAdminController@application');
	// Route::get('/UserAdminSupport', 'UserAdminController@support');
	// Route::get('/UserAdminPromotion', 'UserAdminController@promotion');
	// Route::get('/UserAdminApplicationList', 'UserAdminController@application_list');
	// Route::get('/UserAdminApplicationHistory', 'UserAdminController@application_history');
	// Route::get('/UserAdminOpenSupport', 'UserAdminController@open_support');
	// Route::get('/UserAdminCreateSubUser', 'UserAdminController@create_sub_user');
	// /*Accounts Dashboard*/
	// Route::get('/AccountsDashboard', 'AccountsController@dashboard');
	// Route::get('/AccountsUser', 'AccountsController@user');
	// Route::get('/AccountsCompanyInfo', 'AccountsController@companyinfo');
// Route::get('/AccountsAccountHead', 'AccountsController@accounthead');