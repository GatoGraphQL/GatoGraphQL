<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_TemplateManager_Hooks {

	function __construct() {

		add_action('PoP_Engine:output:end', array($this, 'save_user_meta'));
		add_action('PoP_Engine:output_json:end', array($this, 'save_user_meta'));
	}

	function save_user_meta() {
		GD_TemplateManager_UserMetaUtils::save_user_meta();
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_TemplateManager_Hooks();
