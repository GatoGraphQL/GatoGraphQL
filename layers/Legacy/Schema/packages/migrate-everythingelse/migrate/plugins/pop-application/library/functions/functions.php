<?php
use PoP\ComponentModel\ModuleProcessors\Constants;

\PoP\Root\App::getHookManager()->addFilter(
	Constants::HOOK_QUERYDATA_WHITELISTEDPARAMS, 
	function($params) {
	    $params[] = GD_URLPARAM_TIMESTAMP;
	    return $params;
	}
);
