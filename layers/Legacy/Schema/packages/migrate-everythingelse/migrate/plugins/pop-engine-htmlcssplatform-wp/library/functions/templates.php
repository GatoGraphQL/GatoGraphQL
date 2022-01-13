<?php

use PoP\ComponentModel\Facades\HelperServices\ApplicationStateHelperServiceFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addFilter(
	'template_include', 
	function ($template) {
	    // If the theme doesn't implement the template, use the default one
		$applicationStateHelperService = ApplicationStateHelperServiceFacade::getInstance();
		if (!$applicationStateHelperService->doingJSON()) {
	        return POP_ENGINEHTMLCSSPLATFORM_TEMPLATES.'/index.php';
	    }
	    return $template;
	},
	// Priority: execute before PoP Engine
	// @todo Revise if this is still correct
	PHP_INT_MAX - 1
);
