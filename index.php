<?php
	include dirname(__FILE__) . '/ASEngine/AS.php';
	include dirname(__FILE__) . '/minifier.php';
	
	$httpMethod = $_SERVER['REQUEST_METHOD'];
	$uri = $_SERVER['REQUEST_URI'];
	
	if (false !== $pos = strpos($uri, '?')) {
		$uri = substr($uri, 0, $pos);
	}
	$uri = rawurldecode($uri);
	
	$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
	
	switch ($routeInfo[0]) {
		case \FastRoute\Dispatcher::NOT_FOUND:
        include dirname(__FILE__) . PAGE_404;
        break;
		case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        include dirname(__FILE__) . PAGE_405;
        break;
		case \FastRoute\Dispatcher::FOUND:
		if(strpos($routeInfo[1], '@') !== false){
			list($class, $method) = explode("@", $routeInfo[1], 2);
			app($class)->{$method}($routeInfo[2]);
			}elseif(strpos($routeInfo[1], ':') !== false){
			list($class, $method) = explode(":", $routeInfo[1], 2);
			call_user_func(array(new ASView($routeInfo[2],$method),$class), $routeInfo[2]);
			}elseif(strpos($routeInfo[1], '/') !== false){
			list($class, $method) = explode("/", $routeInfo[1], 2);
			call_user_func(array(new $class($routeInfo[2]),$method), $routeInfo[2]);
			}else{
			call_user_func(array(new ASView($routeInfo[2]),$routeInfo[1]), $routeInfo[2]);
		}
        break;
	}
	
	
	
	
?>