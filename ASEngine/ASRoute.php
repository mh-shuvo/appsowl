<?php
	
	if(DEFAULT_PAGE == "lp"){
		$route->get('/', 'view:lp/home');
		$route->get('/logout/', 'getLogout');
	}
	
	if(DEFAULT_PAGE == "web"){
		$route->get('/', 'view:web/home');
		$route->get('/verify', 'view:web/verify');
		$route->get('/pricing', 'view:web/pricing');
		$route->get('/pos-details', 'view:web/pos-details');
		$route->get('/tutorial', 'view:web/tutorial');
		$route->get('/forget-password', 'view:web/forget-password');
		$route->get('/blog', 'view:web/blog');
		$route->get('/soft-reg/{id}/', 'view:soft-reg');
		$route->get('/dashboard','view:web/dashboard');
		$route->get('/account-setting','view:web/account-setting');
		$route->get('/history','view:web/history');
		$route->get('/install/{id}/','view:web/install');
		$route->get('/module[/{id}]', 'view:web/module');
		$route->get('/payment-history','view:web/payment-history');
		$route->get('/payment-history/{id}/','view:web/payment-history');
		$route->get('/subscribe-pos-setting','view:web/subscribe-pos-setting');
		$route->get('/pos-setting','view:web/pos-setting');
		$route->get('/agent','view:web/agent');
		$route->get('/logout/', 'getLogout');
		$route->post('/payment-callback/', 'payment@getPaymentCallBack');
		$route->get('/agent-info/','view:web/agent-info');
		$route->get('/terms/','view:web/terms_use');
		$route->get('/privacy/','privacy');
		$route->get('/renew','view:web/renew');
		$route->get('/fund','view:web/fund');
		$route->get('/withdrawal','view:web/withdrawal');
		$route->get('/voucher','view:web/voucher');
	}
	
	$route->addGroup('/agent', function (\FastRoute\RouteCollector $route) {
		$route->get('/','view:agent/home');
		$route->get('/home','view:agent/home');
		$route->get('/new-user','view:agent/new-user');
		$route->get('/user-info','view:agent/user-info');
		$route->get('/subscribe-history[/{id}]','view:agent/subscribe-history');
		$route->get('/profile','view:agent/profile');
		$route->get('/received-payment','view:agent/received-payment');
		$route->get('/withdrwal-payment','view:agent/withdrwal-payment');
		
	});	
	
	$route->addGroup('/pos', function (\FastRoute\RouteCollector $route) {
		//--------Core Start ----------//
		$route->get('/','view:pos/home');
		$route->get('/dashboard','view:pos/dashboard');
		$route->get('/logout/', 'getLogout');
		$route->get('/lock','view:pos/lock-screen');
		$route->get('/home','view:pos/home');
		$route->get('/product[/{id}]','view:pos/ad-product');
		$route->get('/product-list','view:pos/product-list');
		$route->get('/unit','view:pos/unit');
		$route->get('/category','view:pos/category');
		$route->get('/brand','view:pos/brand');
		$route->get('/variation','view:pos/variation');
		$route->get('/purchase-list', 'view:pos/purchase-list');
		$route->get('/pos-sales-list', 'view:pos/pos-sales-list');
		$route->get('/setting', 'view:pos/pos-setting');
		$route->get('/account-setting', 'view:pos/account-setting');
		$route->get('/profit-loss', 'view:pos/profit-loss-report');
		$route->get('/purchase-report', 'view:pos/purchase-report');
		$route->get('/sell-report', 'view:pos/sell-report');
		$route->get('/contact-report', 'view:pos/supplier-customer-report');
		$route->get('/vat-report', 'view:pos/vat-report');
		$route->get('/customer','view:pos/customer');
		$route->get('/supplier-view/{id}','view:pos/supplier-view');
		$route->get('/customer-view/{id}','view:pos/customer-view');
		$route->get('/supplier','view:pos/supplier');
		$route->get('/simple-purchase', 'view:pos/simple-purchase');
		$route->post('/ajax/', 'post:ASPosAjax');
		$route->post('/modal/', 'post:ASPosModal');			
		$route->get('/purchase-sell-report', 'view:pos/purchaseSellReport');
		$route->get('/stock-report', 'view:pos/stock-report');
		$route->get('/plugins-status[/{id}]', 'view:pos/plugins-status');
		$route->get('/test', 'view:pos/test');
		$route->get('/ticket_list', 'view:pos/ticket-list');
		$route->get('/ticket_view/{id}', 'view:pos/ticket-view');
		$route->get('/print-barcode', 'view:pos/print-barcode');
		//--------Core END ----------//
		
		// income,expense,account_chart,new_account,transfer,assets
		
		//--------Plugins Start----------//
		
		$route->get('/purchase[/{id}]','loadplugin:add_purchase');
		$route->get('/pos-sales[/{id}]', 'loadplugin:add_pos_sales');	
		$route->get('/manage-user', 'loadplugin:manage_user');	
		$route->get('/new-user[/{id}]', 'loadpluginextra:manage_user(create_new)');	
		$route->get('/store', 'loadplugin:multiple_store_warehouse');	
		$route->get('/warehouse', 'loadpluginextra:multiple_store_warehouse(warehouse)');	
		$route->get('/invoice-setting', 'loadplugin:invoice_setting');	
		
		$route->get('/accounts', 'loadplugin:accounts');	
		$route->get('/chart-accounts', 'loadpluginextra:accounts(accounts_chart)');	
		$route->get('/income', 'loadpluginextra:accounts(income)');
		$route->get('/expense', 'loadpluginextra:accounts(expense)');
		$route->get('/capital', 'loadpluginextra:accounts(capital)');
		$route->get('/withdraw', 'loadpluginextra:accounts(withdraw)');
		$route->get('/account-new', 'loadpluginextra:accounts(new_account)');
		$route->get('/transfer', 'loadpluginextra:accounts(transfer)');
		$route->get('/income-statement', 'loadpluginextra:accounts(income_statement)');
		$route->get('/financial-statement', 'loadpluginextra:accounts(financial_statement)');
		$route->get('/owner-equity', 'loadpluginextra:accounts(owner_equity)');
		$route->get('/trial-balance', 'loadpluginextra:accounts(trial-balance)');
		$route->get('/account-user', 'loadpluginextra:accounts(account_user)');
		$route->get('/expense-report[/{id}]', 'view:pos/expense_report');
		$route->get('/income-report[/{id}]', 'view:pos/income_report');
		$route->get('/chart-report[/{id}]', 'view:pos/account_chart_report');
		
		$route->get('/purchase-return/{id}', 'loadplugin:purchase_return');
		$route->get('/return-purchase-list', 'loadpluginextra:purchase_return(purchase-return-list)');

		$route->get('/sale-return/{id}', 'loadplugin:sale_return');
		$route->get('/sell-return-report', 'loadpluginextra:sale_return(return-sale-list)');
		
		$route->get('/invoice-sales[/{id}]', 'loadplugin:invoice_sale');
		$route->get('/invoice-sales-list', 'loadpluginextra:invoice_sale(invoice-sale-list)');
		$route->get('/sales-view/{id}', 'loadpluginextra:invoice_sale(sales-view)');
		$route->get('/challan/{id}', 'loadpluginextra:invoice_sale(challan)');
		
		$route->get('/multiple-payment-method', 'loadplugin:multiple_payment_method');
		$route->get('/payment-transfer', 'loadpluginextra:multiple_payment_method(payment-transfer)');
		
		$route->get('/stock-transfer[/{id}]','loadplugin:stock_transfer');
		$route->get('/stock-transfer-list','loadpluginextra:stock_transfer(stock-transfer-list)');
		
		$route->get('/stock-adjustment[/{id}]','loadplugin:stock_adjustment');
		$route->get('/stock-adjustment-list','loadpluginextra:stock_adjustment(stock-adjustment-list)');
		$route->get('/stock-adjustment-report','loadpluginextra:stock_adjustment(stock-adjustment-report)');
		
		$route->get('/quotations-list','loadplugin:quotations');

		//--------Plugins END----------//
		$route->get('/registry', 'view:pos/registry');
		$route->get('/product-sale-report','view:pos/sell-report');
		$route->get('/purchase-payment-report','view:pos/purchase-payment-report');
		$route->get('/sale-payment-report','view:pos/sale-payment-report');
		$route->get('/register-report','view:pos/register-report');
		// $route->get('/','view:');
		
		
		
		// $route->get('/purchase-return[/{id}]','view:pos/purchase-return');
		// $route->get('/adv_purchase','view:pos/purchase');
		
		// $route->get('/multi_serial_product','view:pos/multi-serial-product');
		
		// $route->get('/return-purchase-list', 'view:pos/return-purchase-list');
		// $route->get('/return-purchase-list/edit/{id}', 'view:pos/return-purchase-edit');
		// $route->get('/add_sales[/{id}]', 'view:pos/add-sales');
		
		// $route->get('/invoice_sales_list', 'view:pos/invoice-sales-list');
		// $route->get('/list_quotation', 'view:pos/list-quotation');
		// $route->get('/warehouse', 'view:pos/warehouse');
		// $route->get('/list-sell-return', 'view:pos/list-sell-return');
		
		

		// $route->get('/total-vat', 'view:pos/total-vat');
		// $route->get('/paid-vat', 'view:pos/paid-vat');
		

		
		
		// $route->get('/purchase-payment-report', 'view:pos/purchase-payment-report');
		// $route->get('/sell-payment-report', 'view:pos/sell-payment-report');
		// $route->get('/expens-report', 'view:pos/expens-report');
		// $route->get('/balance-report', 'view:pos/balence-report');
		// $route->get('/register-report', 'view:pos/register-report');
		
		
		// $route->get('/stock_adustment-report', 'view:pos/stock_adjustment_report');
		// $route->get('/sr-report', 'view:pos/sr_report');
		// $route->get('/new-user[/{id}]', 'view:pos/new-user');
		
		// $route->get('/list-stock-transfer', 'view:pos/list-stock-transfer');
		// $route->get('/stock-transfer', 'view:pos/stock-transfer');
		// $route->get('/income-list', 'view:pos/income-list');
		// $route->get('/expense-list', 'view:pos/expense-list');
		// $route->get('/store-list', 'view:pos/store-list');
		// $route->get('/stock-adjustment', 'view:pos/stock-adjustment');
		// $route->get('/list-stock-adjustment', 'view:pos/list-stock-adjustment');
		// $route->get('/sales-invoice/{id}', 'view:pos/sales-view');
		// $route->get('/invoice-print/{id}', 'view:pos/invoice-print');
		// $route->get('/challan/{id}', 'view:pos/challan');
		
		// $route->get('/account-chart', 'view:pos/chart-accounts');
		// $route->get('/invoice-setting', 'view:pos/invoice-setting');
		$route->get('/pdf/{id}', 'view:pos/pdf-test');
		$route->get('/print-challan/{id}', 'view:pos/pdf-challan');
		$route->get('/access-denied', 'view:pos/403');
				
		$route->post('/filter/', 'post:ASPosFilter');
		$route->get('/image/[{id}]', 'pos@getPosImages');
	});
	
	$route->post('/ajax/', 'post:ASAjax');
	
	
	
	
