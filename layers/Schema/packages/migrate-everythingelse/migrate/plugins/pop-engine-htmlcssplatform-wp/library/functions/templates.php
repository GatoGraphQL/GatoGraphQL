<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter(
	'template_include', 
	function ($template) {
	    // If the theme doesn't implement the template, use the default one
	    if (!doingJson()) {
	        return POP_ENGINEHTMLCSSPLATFORM_TEMPLATES.'/index.php';
	    }
	    return $template;
	},
	// Priority: execute before PoP Engine
	PHP_INT_MAX-1
);
