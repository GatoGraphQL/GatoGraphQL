<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class AAL_PoPProcessors_ProcessorHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_UserAccountUtils:loggedinuserdata:blocks', 
			array($this, 'get_loggedinuserdata_blocks')
		);
		add_filter(
			'GD_Template_Processor_UserAccountUtils:login:blocks', 
			array($this, 'get_login_blocks')
		);
	}

	function get_loggedinuserdata_blocks($blocks) {

		// Add the Notifications since the last user time the user fetched content from website
		$blocks[] = GD_TEMPLATE_BLOCKDATA_LATESTNOTIFICATIONS;
		return $blocks;
	}

	function get_login_blocks($blocks) {

		// Add the Notifications since the last user time the user fetched content from website
		$blocks[] = GD_TEMPLATE_BLOCK_LATESTNOTIFICATIONS;
		return $blocks;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AAL_PoPProcessors_ProcessorHooks();
