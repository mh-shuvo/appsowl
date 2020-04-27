<?php
	
	/*Super Admin Dashboard*/
	
	Route::get('/user-manage', 'SuperAdminController@user_manage')->name('user_manage');
	Route::get('/user-view/{id}', 'SuperAdminController@user_view');
	Route::post('/user-status-change', 'SuperAdminController@user_status_change');
	Route::get('/payment-details/{id}', 'SuperAdminController@payment_details');
	Route::post('/add-fund-submit', 'SuperAdminController@add_fund_submit');

	Route::get('/agent-manage', 'SuperAdminController@agent_manage')->name('agent_user_manage');
	Route::get('/subdomain-manage', 'SuperAdminController@subdomain_manage');
	Route::get('/agent-details/{id}', 'SuperAdminController@agentdetails');
	Route::post('/commission-submit','SuperAdminController@commission_submit');

	Route::get('/withdraw-manage', 'SuperAdminController@withdraw_manage');
	Route::get('/withdraw-details/{id}', 'SuperAdminController@withdraw_details');
	Route::get('/withdraw-status-change/{id}/{status}', 'SuperAdminController@withdraw_status_change');
	Route::post('/withdrawal-pay', 'SuperAdminController@withdrawal_pay');
	
	Route::get('/super-admin-manage', 'SuperAdminController@super_admin_manage')->name('manage_superadmin');
	Route::post('/super-admin-submit','SuperAdminController@super_admin_submit');
	Route::get('/superadmin-status-change/{id}', 'SuperAdminController@super_admin_status_change');

	// Subscription //

	Route::get('/subscribe-list', 'SuperAdminController@subscribelist');
	Route::post('/subscribe-change', 'SuperAdminController@subscribe_change');
	Route::post('/subscribe-edit', 'SuperAdminController@subscribe_edit');
	// Route::get('/subscribe-details/{id}', 'SuperAdminController@subscribe_details');
	// Route::get('/manual-active/{id}', 'SuperAdminController@manualactive');

	// Agent //

	Route::post('/agent-pay', 'SuperAdminController@agent_pay');

	// Promocode //

	Route::get('/voucher-manage','SuperAdminController@voucher');
	Route::post('/promocode-submit','SuperAdminController@promocode_submit');
	Route::post('/voucher-status','SuperAdminController@voucher_status');

	// Software //

	Route::get('/software-list', 'SuperAdminController@software_list');
	Route::get('/software-variations/{id}', 'SuperAdminController@software_variations');
	Route::post('/variation-edit', 'SuperAdminController@variation_edit');

	// Pos Requirements //

	Route::get('/pos-requirements', 'SuperAdminController@pos_requirements');

	// SMS //

	Route::get('/sms-send', 'HomeController@sms_send');	
	Route::post('/sms-send-submit','HomeController@sms_send_submit');
	Route::get('/sms-log', 'HomeController@sms_log');

	// Activity Log //

	Route::get('/activity-log', 'HomeController@activity_log');
	
	// Route::get('/SuperAdminCareers', 'SuperAdminController@careers');
	// Route::get('/SuperAdminService', 'SuperAdminController@service')->name('service_manage');
	// Route::post('/SuperAdminServiceAdd', 'SuperAdminController@service_add');
	// Route::post('/SuperAdminServiceUpdate', 'SuperAdminController@serviceUpdate');
	// Route::get('/SuperAdminServiceDelete/{id}', 'SuperAdminController@service_delete');
	
	// Route::get('/SuperAdminTutorial', 'SuperAdminController@tutorial')->name('manage_tutorial');
	// Route::post('/SuperAdminTutorialAdd', 'SuperAdminController@tutorial_add');
	// Route::post('/SuperAdminTutorialUpdate', 'SuperAdminController@tutorial_update');
	// Route::get('/SuperAdminTutorialDelete/{id}', 'SuperAdminController@tutorial_delete');
	// Route::get('/SuperAdminTutorialView/{id}', 'SuperAdminController@tutorial_view');
	// Route::get('/BalanceReport', 'SuperAdminController@test');
	
	// Route::get('/SuperAdminApplication', 'SuperAdminController@application');
	// Route::get('/SuperAdminSupport', 'SuperAdminController@support');
	// Route::get('/SuperAdminMessageManage', 'SuperAdminController@message_manage');
	
	// Route::get('/SuperAdminNotificationManage', 'SuperAdminController@notification_manage')->name('notification_manage');
	// Route::post('/SuperAdminNotificationAdd', 'SuperAdminController@notification_add');
	// Route::get('/SuperAdminNotificationView/{id}/', 'SuperAdminController@notification_view');
	// Route::get('/SuperAdminNotificationDelete/{id}','SuperAdminController@notification_delete');
	// Route::get('/SuperAdminNotificationEdit/{id}','SuperAdminController@notification_edit');
	// Route::post('/SuperAdminNotificationUpdate','SuperAdminController@notification_update');
	// Route::get('/SuperAdminRemoveNotificationFile/{name}/{n_id}','SuperAdminController@remove_single_notificatin_file');