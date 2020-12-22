<?php
use PoP\LooseContracts\AbstractLooseContractSet;

class PoP_Multilingual_LooseContracts extends AbstractLooseContractSet
{
	/**
     * @return string[]
     */
    public function getRequiredHooks(): array {
		return [
			// Filters
			'popcomponent:multilingual:notavailablecontenttranslation',
		];
	}
}

/**
 * Initialize
 */
new PoP_Multilingual_LooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

