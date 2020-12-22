<?php
namespace PoP\EngineWebPlatform;
use PoP\LooseContracts\AbstractLooseContractSet;

class WebPlatformCMSLooseContracts extends AbstractLooseContractSet
{
	/**
     * @return string[]
     */
    public function getRequiredHooks(): array {
		return [
			// Actions
			'popcms:enqueueScripts',
			'popcms:printFooterScripts',
			'popcms:printScripts',
			// Filters
			'popcms:scriptSrc',
			'popcms:scriptTag',
		];
	}
}

/**
 * Initialize
 */
new WebPlatformCMSLooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

