<?php
define('POP_STRATUM_CONFIGURATION', 'configuration');

class GD_Stratum_Configuration extends GD_Stratum_Data
{
	public function getStratum()
    {
        return POP_STRATUM_CONFIGURATION;
    }

    public function getStrata() {
    	return array_merge(
    		parent::getStrata(),
    		[
    			POP_STRATUM_CONFIGURATION
    		]
    	);
    }
}

/**
 * Initialization
 */
new GD_Stratum_Configuration();
