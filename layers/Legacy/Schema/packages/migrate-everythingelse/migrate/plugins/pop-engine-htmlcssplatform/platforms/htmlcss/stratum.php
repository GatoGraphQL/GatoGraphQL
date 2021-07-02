<?php
use PoP\Hooks\Facades\HooksAPIFacade;

define('POP_STRATUM_HTMLCSS', 'htmlcss');

class GD_Stratum_HTMLCSS extends GD_Stratum_Configuration
{
	public function getStratum()
    {
        return POP_STRATUM_HTMLCSS;
    }

    public function getStrata() {
    	return array_merge(
    		parent::getStrata(),
    		[
    			POP_STRATUM_HTMLCSS
    		]
    	);
    }
}

/**
 * Initialization
 */
new GD_Stratum_HTMLCSS();
