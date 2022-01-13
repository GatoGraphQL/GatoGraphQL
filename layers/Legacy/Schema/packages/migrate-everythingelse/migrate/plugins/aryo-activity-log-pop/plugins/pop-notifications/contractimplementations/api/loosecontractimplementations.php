<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;

class PoP_AAL_Notifications_LooseContractImplementations
{
	public function __construct() {
		
		$hooksapi = \PoP\Root\App::getHookManager();

		// Actions
		$hooksapi->addAction('AAL_PoP_Hooks', function() use($hooksapi) {
			return $hooksapi->doAction('popcomponent:notifications:init');
		});

		$loosecontract_manager = LooseContractManagerFacade::getInstance();
		$loosecontract_manager->implementHooks([
			'popcomponent:notifications:init',
		]);
	}
}

/**
 * Initialize
 */
new PoP_AAL_Notifications_LooseContractImplementations();

