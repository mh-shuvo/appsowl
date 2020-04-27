<?php
namespace App\Http\Middleware;

use Closure;
use Session;

class Maintainer{
    
    public function handle($request, Closure $next)
    {
    	$permission = Session::get('admin_data')[0] ['permission'];

        if (strpos($permission, ',') !== false) {
    	    $permission = explode(',', $permission);
    		$count = count($permission);
    		for ($x = 0; $x < $count; $x++) {
    			if ($permission[$x]=='maintainer'){
    			    return $next($request);
    			}
    		}
    	}elseif ($permission=='maintainer'){
    		return $next($request);
		}

		return redirect('/login');
        
    }
}