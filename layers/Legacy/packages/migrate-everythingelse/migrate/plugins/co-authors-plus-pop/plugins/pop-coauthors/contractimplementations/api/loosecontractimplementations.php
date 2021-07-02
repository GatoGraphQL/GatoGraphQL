<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;

class CAP_PoP_Coauthors_LooseContractImplementations
{
	function __construct() {
		
		$hooksapi = HooksAPIFacade::getInstance();
		$loosecontract_manager = LooseContractManagerFacade::getInstance();

		// Filters
		$hooksapi->addFilter('coauthors_supported_post_types', function($post_types) use($hooksapi) {
			return $hooksapi->applyFilters('popcomponent:coauthors:supportedposttypes', $post_types);
		}, 10, 1);

		$loosecontract_manager->implementHooks([
			'popcomponent:coauthors:supportedposttypes',
		]);
	}
}

/**
 * Initialize
 */
new CAP_PoP_Coauthors_LooseContractImplementations();

