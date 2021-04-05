<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\RouteHookNames;

HooksAPIFacade::getInstance()->addFilter(
    RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_POSTCATEGORIES_ROUTE_POSTCATEGORIES,
    		]
    	);
    }
);
