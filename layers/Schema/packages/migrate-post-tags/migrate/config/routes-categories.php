<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter(
    'routes',
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_POSTCATEGORIES_ROUTE_POSTCATEGORIES,
    		]
    	);
    }
);
