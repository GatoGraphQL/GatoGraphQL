<?php

// const STANDARDNATURE_ROUTE_QUERY = [
// 	'post_type' => 'page',
// 	'page_id' => POPENGINE_PAGEPLACEHOLDER_ROUTE,
// ];
const STANDARDNATURE_ROUTE_QUERY = [
	'some_hacky_unique_key_for_PoP' => 'some_hacky_unique_value_for_PoP',
];

use Brain\Cortex\Route\RouteCollectionInterface;
use Brain\Cortex\Route\QueryRoute;

// function getRouteQuery(array $matches) {
// 	return [
// 		'post_type' => 'page',
// 		'page_id' => POPENGINE_PAGEPLACEHOLDER_ROUTE,
// 	];
// }

add_action('cortex.routes', function(RouteCollectionInterface $routes) {	
	foreach (\PoP\CMS\RouteProvider::getRoutes() as $route) {
		$routes->addRoute(new QueryRoute(
			$route,
			function (array $matches) { // 'getRouteQuery'
				return STANDARDNATURE_ROUTE_QUERY;
			}
		));
	}
});