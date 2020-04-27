<?php
	
	//    ---------- Web module --------------

	// Career //	
	
	Route::get('/career-manage', 'WebController@career');

	// Tutorial //

	Route::get('/tutorial-manage', 'WebController@tutorial');
	Route::post('/tutorial-submit','WebController@tutorial_submit');
	Route::get('/tutorial-edit/{id}','WebController@tutorial_edit');
	Route::post('/tutorial-update','WebController@tutorial_update');
	Route::get('/tutorial-status/{id}','WebController@tutorial_status');
	Route::get('/tutorial-delete/{id}','WebController@tutorial_delete')->name('tutorial-delete');

	// Notification //

	Route::get('/notification-manage','WebController@notification');
	Route::post('/notification-submit','WebController@notification_submit');
	Route::get('/notification-edit/{id}','WebController@notification_edit');
	Route::post('/notification-update','WebController@notification_update');
	Route::post('/notification-status-change','WebController@notification_status_change');
	Route::get('/notification-delete/{id}','WebController@notification_delete')->name('notification-delete');
	
	// Terms & Condition //

	Route::get('/terms-condition','WebController@terms_condition_manage');
	Route::get('/terms-condition/add','WebController@create_terms_condition');
	Route::post('/terms-condition/add','WebController@store_terms_condition');
	Route::get('/terms-condition/edit/{id}','WebController@edit_terms_condition');
	
	// Privacy & Policy //

	Route::get('/privacy','WebController@privacy_manage');
	Route::get('/privacy/add','WebController@create_privacy');
	Route::post('/privacy/add','WebController@store_privacy');
	Route::get('/privacy/edit/{id}','WebController@edit_privacy');
	Route::get('/terms-privacy/status_change/{id}','WebController@Terms_PrivacyStatusChange');