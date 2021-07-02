<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\Constants;

HooksAPIFacade::getInstance()->addFilter(
	Constants::HOOK_QUERYDATA_WHITELISTEDPARAMS, 
	function($params) {
	    $params[] = GD_URLPARAM_TIMESTAMP;
	    return $params;
	}
);
