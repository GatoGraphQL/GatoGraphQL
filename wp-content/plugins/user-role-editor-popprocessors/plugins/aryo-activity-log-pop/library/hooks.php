<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * AAL Hook Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class URE_AAL_Hooks {

	function __construct() {

		add_filter(
			'AAL_PoP_API:additional_notificatios:markasread:users:actions', 
			array($this, 'add_useractions')
		);
		add_filter(
			'AAL_PoP_API:additional_notificatios:markasread:users:actions', 
			array($this, 'add_sameuseractions')
		);
	}

	function add_useractions($actions) {

		$actions[] = URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY;
		return $actions;
	}

	function add_sameuseractions($actions) {

		$actions[] = URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP;
		return $actions;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new URE_AAL_Hooks();
