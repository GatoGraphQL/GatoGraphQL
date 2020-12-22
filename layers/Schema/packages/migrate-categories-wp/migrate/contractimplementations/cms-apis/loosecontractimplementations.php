<?php
namespace PoPSchema\Categories\WP;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;
use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class CMSLooseContractImplementations extends AbstractLooseContractResolutionSet
{
	protected function resolveContracts()
    {
		$this->nameResolver->implementNames([
			'popcms:dbcolumn:orderby:categories:count' => 'count',
			'popcms:dbcolumn:orderby:categories:id' => 'term_id',
		]);
	}
}

/**
 * Initialize
 */
new CMSLooseContractImplementations(
	LooseContractManagerFacade::getInstance(),
	NameResolverFacade::getInstance(),
	HooksAPIFacade::getInstance()
);

