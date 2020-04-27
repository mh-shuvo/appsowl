<?php
	
	define('AS_VERSION', '3.0.0');
	
	// Debug Mode
	// DON'T FORGET TO SET IT TO FALSE FOR PRODUCTION!
	define('DEBUG', false);
	
	// If debug mode is turned on, we will
	// show all errors to the user.
	if (DEBUG) {
		ini_set("display_errors", "1");
		error_reporting(E_ALL);
	}
	
	include_once dirname(__FILE__) . "/../vendor/autoload.php";
	
	ASSession::startSession();
	
	$container = new Pimple\Container();
	
	$container['root'] = function () {
		try {
			$db = new ASDatabase(ROOT_DB_TYPE, ROOT_DB_HOST, ROOT_DB_NAME, ROOT_DB_USER, ROOT_DB_PASS);
			$db->debug(DEBUG);
			return $db;
			} catch (PDOException $e) {
			die('Connection failed: ' . $e->getMessage());
		}
	};
	
	$container['db'] = function () {
		try {
			$db = new ASDatabase(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
			$db->debug(DEBUG);
			return $db;
			} catch (PDOException $e) {
			die('Connection failed: ' . $e->getMessage());
		}
	};
	
	$container['mailer'] = $container->factory(function () {
		return new ASEmail;
	});
	
	$container['hasher'] = $container->factory(function () {
		return new ASPasswordHasher;
	});
	
	$container['validator'] = $container->factory(function ($c) {
		return new ASValidator($c['root']);
	});
	
	$container['login'] = $container->factory(function ($c) {
		return new ASLogin($c['root'], $c['hasher']);
	});
	
	$container['register'] = $container->factory(function ($c) {
		return new ASRegister($c['root'], $c['mailer'], $c['validator'], $c['login'], $c['hasher']);
	});
	
	$container['user'] = $container->factory(function ($c) {
		return new ASUser($c['root'], $c['hasher'], $c['validator'], $c['login'], $c['register']);
	});
	
	$container['pos'] = $container->factory(function ($c) {
		return new ASPos($c['db'], $c['user']);
	});
	
	$container['admin'] = $container->factory(function ($c) {
		return new ASAdmin($c['db'], $c['root'], $c['user']);
	});
	
	$container['payment'] = $container->factory(function ($c) {
		return new ASPayment($c['root'], $c['user']);
	});
	
	$container['lp'] = $container->factory(function ($c) {
		return new ASLp($c['db'], $c['user']);
	});
	
	$container['role'] = $container->factory(function ($c) {
		return new ASRole($c['root'], $c['validator']);
	});
	
	$container['current_user'] = function ($c) {
		if (! $c['login']->isLoggedIn()) {
			return null;
		}
		
		$result = $c['root']->select(
        "SELECT as_users.*, as_user_details.*, as_user_roles.role 
        FROM as_users, as_user_details, as_user_roles 
        WHERE as_users.user_id = :id
        AND as_user_details.user_id = as_users.user_id
        AND as_user_roles.role_id = as_users.user_role",
        array("id" => ASSession::get('user_id'))
		);
		
		if (! $result) {
			return null;
		}
		
		$result = $result[0];
		
		
		return (object) array(
        'id' => (int) $result['user_id'],
        'email' => $result['email'],
        'username' => $result['username'],
        'password' => $result['password'],
        'store_id' => $result['store_id'],
        'domain_id' => $result['domain_id'],
        'added_by' => $result['added_by'],
        'first_name' => $result['first_name'],
        'last_name' => $result['last_name'],
        'confirmed' => $result['confirmed'] == 'Y',
        'sms_confirmed' => $result['sms_confirmed'],
        'country_code' => $result['country_code'],
        'postcode' => $result['postcode'],
        'zone' => $result['zone'],
        'country_name' => $result['country'],
        'agent_id' => $result['agent_id'],
        'role' => $result['role'],
        'role_id' => (int) $result['user_role'],
        'phone' => $result['phone'],
        'address' => $result['address'],
        'is_banned' => $result['banned'] == 'Y',
        'is_admin' => strtolower($result['role']) === 'admin',
        'is_superadmin' => strtolower($result['role']) === 'superadmin',
        'is_user' => strtolower($result['role']) === 'user',
        'is_agent' => strtolower($result['role']) === 'agent',
        'last_login' => $result['last_login']
		);
	};
	
	ASContainer::setContainer($container);
	
	if (isset($_GET['lang'])) {
		ASLang::setLanguage($_GET['lang']);
	}
	
	$dispatcher = \FastRoute\cachedDispatcher(function(\FastRoute\RouteCollector $r) {
		$r->addGroup(WEBSITE_DOMAIN_BASE_URI, function (\FastRoute\RouteCollector $route) {
			include_once dirname(__FILE__) . "/ASRoute.php";
		});
	}, [
    'cacheFile' => __DIR__ . '/../cache/route.cache',
    'cacheDisabled' => ROUTE_CACHED_DISABLE, 
	]);
