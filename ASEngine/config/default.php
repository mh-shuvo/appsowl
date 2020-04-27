<?php
	defined('_AZ') or die('Restricted access');
//Timezone
date_default_timezone_set('Asia/Dhaka');

//WEBSITE

define('WEBSITE_NAME', ROOT_WEBSITE_NAME);
define('WEBSITE_DOMAIN', ROOT_WEBSITE_DOMAIN);

// REGISTRATION CONFIGURATION
define('REGISTER_CONFIRM', ROOT_WEBSITE_DOMAIN."/confirm.php");
define('REGISTER_PASSWORD_RESET', ROOT_WEBSITE_DOMAIN."/passwordreset.php");

// It can be the same as domain (if script is placed on website's root folder)
// or it can contain path that include subfolders, if script is located in
//some subfolder and not in root folder.
define('SCRIPT_URL', ROOT_WEBSITE_DOMAIN);
define('IMAGE_URL', ROOT_WEBSITE_DOMAIN);
define('ASSETS_LOCATION', ROOT_LOCATION."/assets");

//DATABASE CONFIGURATION
define('DB_HOST', ROOT_DB_HOST);
define('DB_TYPE', ROOT_DB_TYPE);
define('DB_USER', ROOT_DB_USER);
define('DB_PASS', ROOT_DB_PASS);
define('DB_NAME', ROOT_DB_NAME);

// TRANSLATION
define('DEFAULT_LANGUAGE', 'en');

define('DEFAULT_VAT', '10'); // Vat %

// Default Page
define('DEFAULT_PAGE', 'web'); // For Landing Page 'lp'  For Ecommarce Page 'ec' 

// SOCIAL LOGIN CONFIGURATION
define('SOCIAL_CALLBACK_URI', ROOT_WEBSITE_DOMAIN."/socialauth_callback.php");


