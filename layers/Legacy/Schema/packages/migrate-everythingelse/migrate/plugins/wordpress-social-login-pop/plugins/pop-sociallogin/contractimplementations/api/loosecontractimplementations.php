<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;

class WSLPoP_SocialLogin_LooseContractImplementations
{
	public function __construct() {
		
		$hooksapi = \PoP\Root\App::getHookManager();
		$loosecontract_manager = LooseContractManagerFacade::getInstance();

		// Actions
		$hooksapi->addAction('wsl_hook_process_login_after_wp_insert_user', function($user_id, $provider) use($hooksapi) {
			return $hooksapi->doAction('popcomponent:sociallogin:usercreated', $user_id, $provider);
		}, 10, 2);

		$loosecontract_manager->implementHooks([
			'popcomponent:sociallogin:usercreated',
		]);
	}
}

/**
 * Initialize
 */
new WSLPoP_SocialLogin_LooseContractImplementations();

