<?php
use PoP\Hooks\Facades\HooksAPIFacade;

define('POP_STRATUM_WEB', 'web');

class GD_Stratum_Web extends GD_Stratum_HTMLCSS
{
    public function getStratum()
    {
        return POP_STRATUM_WEB;
    }

    public function getStrata() {
    	return array_merge(
    		parent::getStrata(),
    		[
    			POP_STRATUM_WEB
    		]
    	);
    }
}

/**
 * Initialization
 */
new GD_Stratum_Web();
