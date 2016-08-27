<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Themes
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_ThemeModeBase {

	function __construct() {

		$this->get_theme()->add_thememode($this);
	}

	function get_theme() {

		return null;
	}

	function get_name() {
		
		return '';
	}

	function add_jsmethod(&$ret, $method, $group = GD_JSMETHOD_GROUP_MAIN, $unshift = false) {
		
		GD_TemplateManager_Utils::add_jsmethod($ret, $method, $group, $unshift);
	}
	function remove_jsmethod(&$ret, $method, $group = GD_JSMETHOD_GROUP_MAIN) {
		
		GD_TemplateManager_Utils::remove_jsmethod($ret, $method, $group);
	}
}