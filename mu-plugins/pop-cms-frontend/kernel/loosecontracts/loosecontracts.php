<?php
namespace PoP\CMS;

class FrontendCMSLooseContracts extends CMSLooseContractsBase
{
	public function getRequiredHooks() {
		return [
			// Actions
			'popcms:footer',
			'popcms:enqueueScripts',
			'popcms:printFooterScripts',
			'popcms:head',
			'popcms:printStyles',
			'popcms:printScripts',
			// Filters
			'popcms:styleSrc',
			'popcms:scriptSrc',
			'popcms:scriptTag',
			'popcms:styleTag',
		];
	}
}

/**
 * Initialize
 */
new FrontendCMSLooseContracts();

