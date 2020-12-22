<?php
use PoP\LooseContracts\AbstractLooseContractSet;

class PoP_Locations_LooseContracts extends AbstractLooseContractSet
{
	/**
     * @return string[]
     */
    public function getRequiredNames(): array {
		return [
			// Options
			'popcomponent:locations:dbcolumn:orderby:locations:name',
		];
	}
}

/**
 * Initialize
 */
new PoP_Locations_LooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

