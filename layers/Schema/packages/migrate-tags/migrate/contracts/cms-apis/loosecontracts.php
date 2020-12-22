<?php
namespace PoPSchema\Tags;
use PoP\LooseContracts\AbstractLooseContractSet;

class CMSLooseContracts extends AbstractLooseContractSet
{
	/**
     * @return string[]
     */
    public function getRequiredNames(): array {
		return [
			// DB Columns
			'popcms:dbcolumn:orderby:tags:count',
			'popcms:dbcolumn:orderby:tags:id',
		];
	}
}

/**
 * Initialize
 */
new CMSLooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

