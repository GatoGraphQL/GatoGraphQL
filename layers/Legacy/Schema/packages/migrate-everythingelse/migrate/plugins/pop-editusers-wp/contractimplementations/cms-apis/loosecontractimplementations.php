<?php
namespace PoP\EditUsers\WP;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;

class CMSLooseContractImplementations
{
	function __construct() {
		
		$hooksapi = HooksAPIFacade::getInstance();

		// Actions
		$hooksapi->addAction('delete_user', function($user_id, $reassign) use($hooksapi) {
			$hooksapi->doAction('popcms:deleteUser', $user_id, $reassign);
		}, 10, 2);
		$hooksapi->addAction('user_register', function($user_id) use($hooksapi) {
			$hooksapi->doAction('popcms:userRegister', $user_id);
		}, 10, 1);

		$loosecontract_manager = LooseContractManagerFacade::getInstance();
		$loosecontract_manager->implementHooks([
			'popcms:deleteUser',
			'popcms:userRegister',
		]);
	}
}

/**
 * Initialize
 */
new CMSLooseContractImplementations();

