<?php
class GD_Stratum_Data extends \PoP\ComponentModel\StratumBase
{
	public function getStratum()
    {
        return \PoP\Engine\Constants\Stratum::DATA;
    }

    public function getStrata() {
    	return [
    		\PoP\Engine\Constants\Stratum::DATA
    	];
    }
}

/**
 * Initialization
 */
new GD_Stratum_Data();
