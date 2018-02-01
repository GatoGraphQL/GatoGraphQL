<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_TemplateManager_Hooks {

	function __construct() {

		add_action('PoP_Engine:rendered', array($this, 'save_user_meta'));
	}

	function save_user_meta() {
		GD_TemplateManager_UserMetaUtils::save_user_meta();
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_TemplateManager_Hooks();
