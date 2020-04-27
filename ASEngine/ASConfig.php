<?php
	define('_AZ', 1);
	
	define('IS_ONLINE', true);
	
	define('ROOT_DOMAIN', "appsowl.com");
	define('ROOT_DOMAIN_LOCAL', "appsowl.com");
	define('BASE_URID', "public_html/");
	
	define('ROOT_WEBSITE_NAME', 'Appsowl');
	define('ROOT_WEBSITE_DOMAIN', "https://appsowl.com");
	
	define('WEBSITE_DOMAIN_BASE_URI', "");
	define('ROUTE_CACHED_DISABLE', true);
	define('ROOT_LOCATION', "/home/appsowl/public_html");
	
	// Root Database
	define('ROOT_DB_HOST', 'localhost');
	define('ROOT_DB_TYPE', 'mysql');
	define('ROOT_DB_USER', 'appsowl_stream');
	define('ROOT_DB_PASS', 'aSq=Pwzec0GI');
	define('ROOT_USER_DB_PASS', 'm3K2S.2%V]9w');
	define('ROOT_USER_DB_NAME', 'appsowl_users');
	define('ROOT_DB_NAME', 'appsowl_stream');
	
	// Redirect user to Defoult config if subdomain config is not found
	$SubDomainName = str_replace(".", "", $_SERVER['SERVER_NAME']);
	if (file_exists(dirname(__FILE__)  .'/config/'.$SubDomainName.'.php') && $_SERVER['SERVER_NAME'] != ROOT_DOMAIN) {
		include dirname(__FILE__) .'/config/'.$SubDomainName.'.php';
		}else{
		include dirname(__FILE__) .'/config/default.php';
	}
	
	define('PAGE_404', "/modules/".DEFAULT_PAGE."/404.php");
	
	define('PAGE_405', "/modules/".DEFAULT_PAGE."/404.php");
	
	define('SUCCESS_LOGIN_REDIRECT', serialize(array('agent' => "/agent/")));
	
	define('MINIFY_ENABLE', false);
	
	//SESSION CONFIGURATION
	define('SESSION_SECURE', true);
	define('SESSION_HTTP_ONLY', false);
	define('SESSION_USE_ONLY_COOKIES', true);
	
	//LOGIN CONFIGURATION
	define('LOGIN_MAX_LOGIN_ATTEMPTS', 20);
	define('LOGIN_FINGERPRINT', true);
	
	//PASSWORD CONFIGURATION
	define('PASSWORD_ENCRYPTION', "bcrypt"); //available values: "sha512", "bcrypt"
	define('PASSWORD_BCRYPT_COST', "13");
	define('PASSWORD_SHA512_ITERATIONS', 25000);
	define('PASSWORD_SALT', "UPj8EVJWvP73FZ9Ih0xzUf"); //22 characters to be appended on first 7 characters that will be generated using PASSWORD_ info above
	define('PASSWORD_RESET_KEY_LIFE', 60);
	
	// REGISTRATION CONFIGURATION
	define('MAIL_CONFIRMATION_REQUIRED', false);
	
	// EMAIL SENDING CONFIGURATION
	// Available MAILER options are 'mail' for php mail() and 'smtp' for using SMTP server for sending emails
	define('MAILER', "mail");
	define('SMTP_HOST', "");
	define('SMTP_PORT', 25);
	define('SMTP_USERNAME', "");
	define('SMTP_PASSWORD', "");
	define('SMTP_ENCRYPTION', "");
	
	define('MAIL_FROM_NAME', "Apps owl");
	define('MAIL_FROM_EMAIL', "noreply@localhost/cms");
	
	// SMS Gateway Details
	define('SMS_API', "R60008905dbaa0127d2233.32195668");
	define('SMS_SENDER', "8809601000800");
	
	// GOOGLE
	define('GOOGLE_ENABLED', false);
	define('GOOGLE_ID', "");
	define('GOOGLE_SECRET', "");
	
	// FACEBOOK
	define('FACEBOOK_ENABLED', false);
	define('FACEBOOK_ID', "");
	define('FACEBOOK_SECRET', "");
	
	// TWITTER
	define('TWITTER_ENABLED', false);
	define('TWITTER_KEY', "");
	define('TWITTER_SECRET', "");
	
	define('POWERED_BY', "www.softwaregalaxyltd.com");
	
	define('DEFAULT_CURRENCY', 'Tk'); // Vat %
	
	define('AGENT_COMMISSION', '20'); //  %
	
	define('USER_WITHDRAWAL_CHARGE', '10'); //  %
	
	//Cpanel Details
	define('CPANEL_USERNAME', "appsowl");
	define('CPANEL_PASSWORD', "quF+b(plggL8");
	define('CPANEL_PREFIX', "appsowl_");
	define('CPANEL_DOMAIN', "appsowl.com");
	define('CPANEL_HOST', "localhost");
	
	//Payment Gateway Details
	
	// define('MERCHANT_ID', "WMX5c57fa21986fa");
	// define('APP_KEY', "0db6c292b9f49ae2b3b06d75ec46fcfeb848a642");
	// define('ACCESS_USERNAME', "appsowl_1882322932");
	// define('ACCESS_PASSWORD', "appsowl_925209045");
	// define('APP_NAME', "appsowl.com");
	// define('PAYMENT_DEFAULT_CURRENCY', "BDT");
	// define('GATEWAY_URL', "https://epay.walletmix.com/check-server");
	// define('CALLBACK_URL', ROOT_WEBSITE_DOMAIN."/payment-callback/");
	// define('PAYMENT_FROM_URL', ROOT_WEBSITE_DOMAIN."/history");
	// define('PAYMENT_CHECKURL', "https://epay.walletmix.com/check-payment");
	
	define('MERCHANT_ID', "WMX5c3722bb30489");
	define('APP_KEY', "43726cfb67b3e48da7343f00f8e3e0bb00c8175b");
	define('ACCESS_USERNAME', "appsowl_2115417106");
	define('ACCESS_PASSWORD', "appsowl_1819180635");
	define('APP_NAME', "appsowl.com");
	define('PAYMENT_DEFAULT_CURRENCY', "BDT");
	define('GATEWAY_URL', "https://sandbox.walletmix.com/check-server");
	define('CALLBACK_URL', ROOT_WEBSITE_DOMAIN."/payment-callback/");
	define('PAYMENT_FROM_URL', ROOT_WEBSITE_DOMAIN."/history");
	define('PAYMENT_CHECKURL', "https://sandbox.walletmix.com/check-payment");
	
	
	
	
