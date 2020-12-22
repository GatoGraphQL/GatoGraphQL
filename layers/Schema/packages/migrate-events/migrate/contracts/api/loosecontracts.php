<?php
use PoP\LooseContracts\AbstractLooseContractSet;

class PoP_Events_LooseContracts extends AbstractLooseContractSet
{
	/**
     * @return string[]
     */
    public function getRequiredNames(): array {
		return [
			// Options
			'popcomponent:events:dbcolumn:orderby:events:startdate',
		];
	}
}

/**
 * Initialize
 */
new PoP_Events_LooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

