<?php
class GD_Stratum_Data extends \PoP\ComponentModel\StratumBase
{
	public function getStratum()
    {
        return \PoP\ConfigurationComponentModel\Constants\Stratum::DATA;
    }

    public function getStrata() {
    	return [
    		\PoP\ConfigurationComponentModel\Constants\Stratum::DATA
    	];
    }
}

/**
 * Initialization
 */
new GD_Stratum_Data();
