<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;

class PoP_Avatar_LooseContractImplementations
{
	public function __construct() {
		
		$hooksapi = \PoP\Root\App::getHookManager();
		$loosecontract_manager = LooseContractManagerFacade::getInstance();

		// Filters
		$hooksapi->addFilter('user-avatar:exists', function($exists, $user_id, $params) use($hooksapi) {
			return $hooksapi->applyFilters('popcomponent:avatar:avatarexists', $exists, $user_id, $params);
		}, 10, 3);

		$loosecontract_manager->implementHooks([
			'popcomponent:avatar:avatarexists',
		]);

		// Actions
		$hooksapi->addAction('gd_user_avatar', function($user_id, $folderpath, $filename, $original, $thumb, $photo) use($hooksapi) {
			return $hooksapi->doAction('popcomponent:avatar:avataruploaded', $user_id, $folderpath, $filename, $original, $thumb, $photo);
		}, 10, 6);

		$loosecontract_manager->implementHooks([
			'popcomponent:avatar:avataruploaded',
		]);
	}
}

/**
 * Initialize
 */
new PoP_Avatar_LooseContractImplementations();

