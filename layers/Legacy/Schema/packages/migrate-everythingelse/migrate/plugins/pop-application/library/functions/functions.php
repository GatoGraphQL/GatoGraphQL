<?php
use PoP\ComponentModel\Constants\HookNames;

\PoP\Root\App::addFilter(
	HookNames::QUERYDATA_WHITELISTEDPARAMS, 
	function($params) {
	    $params[] = GD_URLPARAM_TIMESTAMP;
	    return $params;
	}
);
