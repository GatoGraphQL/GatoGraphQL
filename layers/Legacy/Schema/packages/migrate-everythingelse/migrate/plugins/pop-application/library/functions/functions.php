<?php
use PoP\ComponentModel\Constants\HookNames;

\PoP\Root\App::addFilter(
	HookNames::HOOK_QUERYDATA_WHITELISTEDPARAMS, 
	function($params) {
	    $params[] = GD_URLPARAM_TIMESTAMP;
	    return $params;
	}
);
