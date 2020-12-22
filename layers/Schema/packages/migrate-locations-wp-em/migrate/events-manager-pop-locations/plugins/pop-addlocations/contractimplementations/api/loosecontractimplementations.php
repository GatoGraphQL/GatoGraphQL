<?php
namespace PoP\Engine\WP;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;
use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class EM_PoP_AddLocations_LooseContractImplementations extends AbstractLooseContractResolutionSet
{
	protected function resolveContracts()
    {
		$this->nameResolver->implementNames([
			'popcomponent:addlocations:option:locationDefaultCountry' => 'dbem_location_default_country',
		]);
	}
}

/**
 * Initialize
 */
new EM_PoP_AddLocations_LooseContractImplementations(
	LooseContractManagerFacade::getInstance(),
	NameResolverFacade::getInstance(),
	HooksAPIFacade::getInstance()
);

