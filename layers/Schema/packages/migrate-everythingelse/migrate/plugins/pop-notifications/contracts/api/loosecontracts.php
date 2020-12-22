<?php
use PoP\LooseContracts\AbstractLooseContractSet;

class PoP_Notifications_LooseContracts extends AbstractLooseContractSet
{
	/**
     * @return string[]
     */
    public function getRequiredHooks(): array {
		return [
			// Filters
			'popcomponent:notifications:init',
		];
	}
}

/**
 * Initialize
 */
new PoP_Notifications_LooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

