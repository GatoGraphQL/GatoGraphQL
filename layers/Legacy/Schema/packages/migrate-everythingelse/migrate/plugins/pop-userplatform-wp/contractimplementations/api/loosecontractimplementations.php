<?php
namespace PoP\Engine\WP;
use PoP\LooseContracts\AbstractLooseContractResolutionSet;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;

class PoP_UserPlatformWP_LooseContractImplementations extends AbstractLooseContractResolutionSet
{
	protected function resolveContracts(): void
    {
		$this->nameResolver->implementNames([
			'popcomponent:userplatform:dbcolumn:orderby:users:lastediteddate' => 'meta_value',
		]);
	}
}

/**
 * Initialize
 */
new PoP_UserPlatformWP_LooseContractImplementations(
	LooseContractManagerFacade::getInstance(),
	NameResolverFacade::getInstance(),
	\PoP\Root\App::getHookManager()
);

