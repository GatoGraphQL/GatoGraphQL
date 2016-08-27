<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Themes
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_ThemeManager {

	var $selected_theme, $themes;

	function __construct() {

		$this->themes = array();
		add_action('init', array($this, 'init'));
	}

	function add_theme($theme) {
		
		$this->themes[$theme->get_name()] = $theme;
	}

	function init() {

		// Selected comes in URL param 'theme'
		$selected = $_REQUEST[GD_URLPARAM_THEME];
		
		// Check if the selected theme is inside $themes
		if (!$selected || !in_array($selected, $this->themes)) {
			
			$selected = $this->get_default_themename();
		}

		$this->selected_theme = $selected;
	}

	function get_default_themename() {

		return apply_filters('GD_ThemeManager:default', null);;
	}

	// function get_theme_name() {
	
	// 	return $this->selected_theme;
	// }

	function get_theme() {
	
		return $this->themes[$this->selected_theme];
	}

	function get_thememode() {

		if ($theme = $this->get_theme()) {
			return $theme->get_thememode();
		}

		return '';
	}

	function get_themestyle() {

		if ($theme = $this->get_theme()) {
			return $theme->get_themestyle();
		}

		return '';
	}

	function is_default_theme() {
	
		return $this->selected_theme == $this->get_default_themename();
	}

	function is_default_thememode() {

		if ($theme = $this->get_theme()) {
			return $theme->get_thememode()->get_name() == $theme->get_default_thememodename();
		}

		return false;
	}

	function is_default_themestyle() {

		if ($theme = $this->get_theme()) {
			return $theme->get_themestyle()->get_name() == $theme->get_default_themestylename();
		}

		return false;
	}

	function get_theme_path() {

		if ($theme = $this->get_theme()) {

			// Comment Leo 06/10/2015: Instead of calling function `get_theme_basedir`, use a hook to determine the folder with the templates for the selected theme
			// 2 reasons for this:
			// #1. 	Since splitting into poptheme-wassup and poptheme-wassup-frontend, the logic goes in the 1st but the actual templates in the latter, and the 1st doesn't know which the latter will be
			// #2. 	It allows the templates to be overriden
			// $theme_templates_dir = $theme->get_theme_basedir();

			return GD_ThemeManagerUtils::get_thememode_templates_dir($theme->get_name(), $theme->get_thememode()->get_name());
		}

		return null;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_theme_manager;
$gd_theme_manager = new GD_ThemeManager();
