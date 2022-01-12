<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_UserLogin_Utils
{
    public static function getUserInfo($route = null)
    {
    	if (!$route) {
    			        $route = \PoP\Root\App::getState('route');
    	}
        return PoP_UserState_Utils::routeRequiresUserState($route);
    }
}
