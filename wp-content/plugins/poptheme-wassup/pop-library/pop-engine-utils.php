<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_Engine_Utils {

	// function __construct() {

	// 	add_filter('GD_TemplateManager_Utils:get_vars', array($this, 'get_vars'));
	// }

	public static function get_vars($vars) {

		$target = $vars['target'];
		$fetching_json = $vars['fetching-json'];
		
		$fetching_json_main = ($fetching_json && $target == GD_URLPARAM_TARGET_MAIN);
		$fetching_json_quickview = ($fetching_json && $target == GD_URLPARAM_TARGET_QUICKVIEW);
		$fetching_json_navigator = ($fetching_json && $target == GD_URLPARAM_TARGET_NAVIGATOR);
		$fetching_json_addons = ($fetching_json && $target == GD_URLPARAM_TARGET_ADDONS);
		$fetching_json_modals = ($fetching_json && $target == GD_URLPARAM_TARGET_MODALS);
		
		$vars['fetching-json-main'] = $fetching_json_main;
		$vars['fetching-json-quickview'] = $fetching_json_quickview;
		$vars['fetching-json-navigator'] = $fetching_json_navigator;
		$vars['fetching-json-addons'] = $fetching_json_addons;
		$vars['fetching-json-modals'] = $fetching_json_modals;

		return $vars;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
// new PoPTheme_Wassup_Engine_Utils();
add_filter('GD_TemplateManager_Utils:get_vars', array('PoPTheme_Wassup_Engine_Utils', 'get_vars'));
