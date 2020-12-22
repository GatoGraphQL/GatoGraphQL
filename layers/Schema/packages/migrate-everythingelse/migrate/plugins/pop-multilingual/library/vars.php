<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModelInstance\ModelInstance;

HooksAPIFacade::getInstance()->addFilter(
	ModelInstance::HOOK_COMPONENTS_RESULT, 
	function($components) {
	    // Add the language
	    $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
	    $components[] = $pluginapi->getCurrentLanguage();
	    return $components;
	}
);
