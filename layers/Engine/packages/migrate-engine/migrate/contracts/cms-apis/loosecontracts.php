<?php
namespace PoP\Engine;
use PoP\LooseContracts\AbstractLooseContractSet;

class CMSLooseContracts extends AbstractLooseContractSet
{
	/**
     * @return string[]
     */
    public function getRequiredHooks(): array {
		return [
			// Actions
			'popcms:boot',
			'popcms:init',
			'popcms:shutdown',
			'popcms:componentInstalled',
			'popcms:componentUninstalled',
			'popcms:componentInstalledOrUninstalled',
		];
	}

	/**
     * @return string[]
     */
    public function getRequiredNames(): array {
		return [
			// Options
			'popcms:option:dateFormat',
			'popcms:option:charset',
			'popcms:option:gmtOffset',
			'popcms:option:timezone',
		];
	}
}

/**
 * Initialize
 */
new CMSLooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

