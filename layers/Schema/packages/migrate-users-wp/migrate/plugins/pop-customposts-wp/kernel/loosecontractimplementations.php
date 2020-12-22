<?php
namespace PoPSchema\Users\WP;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;
use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class PostsCMSLooseContractImplementations extends AbstractLooseContractResolutionSet
{
	protected function resolveContracts()
    {
		$this->nameResolver->implementNames([
			'popcms:dbcolumn:orderby:users:post-count' => 'post_count',
		]);
	}
}

/**
 * Initialize
 */
new PostsCMSLooseContractImplementations(
	LooseContractManagerFacade::getInstance(),
	NameResolverFacade::getInstance(),
	HooksAPIFacade::getInstance()
);

