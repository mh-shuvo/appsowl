<?php
	defined('_AZ') or die('Restricted access');
//Timezone
date_default_timezone_set('Asia/Dhaka');

//WEBSITE

define('WEBSITE_NAME', 'Test Company');
define('WEBSITE_DOMAIN', 'https://test.appsowl.com');

// REGISTRATION CONFIGURATION
define('REGISTER_CONFIRM', "https://test.appsowl.com/confirm.php");
define('REGISTER_PASSWORD_RESET', "https://test.appsowl.com/passwordreset.php");

// It can be the same as domain (if script is placed on website's root folder)
// or it can contain path that include subfolders, if script is located in
//some subfolder and not in root folder.
define('SCRIPT_URL', 'https://test.appsowl.com');
define('IMAGE_URL', "https://test.appsowl.com");
define('ASSETS_LOCATION', ROOT_LOCATION."/assets");

//DATABASE CONFIGURATION
define('DB_HOST', 'localhost');
define('DB_TYPE', 'mysql');
define('DB_USER', ROOT_USER_DB_NAME);
define('DB_PASS', ROOT_USER_DB_PASS);
define('DB_NAME', 'appsowl_test');

// TRANSLATION
define('DEFAULT_LANGUAGE', 'en');

define('DEFAULT_VAT', '10'); // Vat %

// Default Page
define('DEFAULT_PAGE', 'lp'); // For Landing Page 'lp'  For Ecommarce Page 'ec' 

// SOCIAL LOGIN CONFIGURATION
define('SOCIAL_CALLBACK_URI', "https://test.appsowl.com/socialauth_callback.php");