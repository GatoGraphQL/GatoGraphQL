<?php
namespace PoPSchema\Users;
use PoP\LooseContracts\AbstractLooseContractSet;

class PostsCMSLooseContracts extends AbstractLooseContractSet
{
	/**
     * @return string[]
     */
    public function getRequiredNames(): array {
		return [
			// DB Columns
			'popcms:dbcolumn:orderby:users:post-count',
		];
	}
}

/**
 * Initialize
 */
new PostsCMSLooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

