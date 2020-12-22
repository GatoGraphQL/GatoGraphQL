<?php
namespace PoPSchema\Pages\WP;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;
use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class CMSLooseContractImplementations extends AbstractLooseContractResolutionSet
{
	protected function resolveContracts()
    {
		// Filters.
		$this->hooksAPI->addFilter('the_title', function($post_title, $post_id) {
			$post_type = get_post_type($post_id);
			if ($post_type == 'page') {
				return $this->hooksAPI->applyFilters('popcms:page:title', $post_title, $post_id);
			}
			return $post_title;
		}, 10, 2);
		
		$this->looseContractManager->implementHooks([
			'popcms:page:title',
			'popcms:page:content',
		]);

		$this->nameResolver->implementNames([
			'popcms:dbcolumn:orderby:pages:date' => 'date',
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

