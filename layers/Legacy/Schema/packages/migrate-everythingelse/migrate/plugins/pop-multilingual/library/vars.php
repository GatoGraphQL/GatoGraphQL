<?php
use PoP\ComponentModel\ModelInstance\ModelInstance;

\PoP\Root\App::addFilter(
	ModelInstance::HOOK_ELEMENTS_RESULT, 
	function($components) {
	    // Add the language
	    $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
	    $components[] = $pluginapi->getCurrentLanguage();
	    return $components;
	}
);
