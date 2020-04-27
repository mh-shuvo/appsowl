<?php
	include dirname(__FILE__) . '/ASEngine/AS.php';
	$results = app('root')->select("SELECT `as_users`.`user_id`, `as_user_details`.`user_id`, `as_user_details`.`agent_id`
	FROM `as_users`, `as_user_details`
	WHERE `as_users`.`user_id` = `as_user_details`.`user_id` AND `as_users`.`user_role` = 3");
	foreach($results as $result){
		// echo $result['user_id'].',';
		app('admin')->subscribeStatusCheck($result['user_id'],$result['agent_id']);
	}
	
