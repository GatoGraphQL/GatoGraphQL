<?php
namespace PoPSchema\Categories;
use PoP\LooseContracts\AbstractLooseContractSet;

class CMSLooseContracts extends AbstractLooseContractSet
{
	/**
     * @return string[]
     */
    public function getRequiredNames(): array {
		return [
			// DB Columns
			'popcms:dbcolumn:orderby:categories:count',
			'popcms:dbcolumn:orderby:categories:id',
		];
	}
}

/**
 * Initialize
 */
new CMSLooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

