<?php
namespace PoP\UserAccount;
use PoP\LooseContracts\AbstractLooseContractSet;

class CMSLooseContracts extends AbstractLooseContractSet
{
	/**
     * @return string[]
     */
    public function getRequiredHooks(): array {
		return [
			// Filters
			'popcms:loginUrl',
			'popcms:lostPasswordUrl',
			'popcms:logoutUrl',
			'popcms:authCookieExpiration',
			'popcms:retrievePasswordTitle',
			'popcms:retrievePasswordMessage',
		];
	}

	/**
     * @return string[]
     */
    public function getRequiredNames(): array {
		return [
			// Capabilities
			'popcms:capability:deletePages',
		];
	}
}

/**
 * Initialize
 */
new CMSLooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

