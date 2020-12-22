<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_UserLogin_Utils
{
    public static function getUserInfo($route = null)
    {
    	if (!$route) {
    		$vars = ApplicationState::getVars();
	        $route = $vars['route'];
    	}
        return PoP_UserState_Utils::routeRequiresUserState($route);
    }
}
