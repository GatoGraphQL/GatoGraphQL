<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * AAL Hook Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_AAL_Hooks {

	function __construct() {

		add_filter(
			'AAL_PoP_Hook_Posts:related_to_post:action', 
			array($this, 'related_to_post_action'), 
			10, 
			2
		);
		add_filter(
			'AAL_PoP_API:additional_notificatios:markasread:posts:actions', 
			array($this, 'add_actions')
		);
	}

	function related_to_post_action($action, $post_id) {

		// If it is an Extract...
		if (gd_get_the_main_category($post_id) == POPTHEME_WASSUP_CAT_HIGHLIGHTS) {

			return AAL_POP_ACTION_POST_REFERENCEDHIGHLIGHTPOST;
		}

		return $action;
	}

	function add_actions($actions) {

		$actions[] = AAL_POP_ACTION_POST_REFERENCEDHIGHLIGHTPOST;
		return $actions;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AAL_Hooks();
