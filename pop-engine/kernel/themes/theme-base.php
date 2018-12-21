<?php
namespace PoP\Engine\Themes;

class ThemeBase {

	var $thememodes, $themestyles;

	function __construct() {

		$this->thememodes = array();
		$this->themestyles = array();

		global $gd_theme_manager;
		$gd_theme_manager->add_theme($this);
	}

	function add_thememode($thememode) {

		$this->thememodes[$thememode->get_name()] = $thememode;
	}

	function add_themestyle($themestyle) {

		$this->themestyles[$themestyle->get_name()] = $themestyle;
	}

	function get_name() {
		
		return '';
	}

	function get_thememodes() {

		return $this->thememodes;
	}

	function get_themestyles() {

		return $this->themestyles;
	}

	function get_default_thememodename() {

		return null;
	}

	function get_default_themestylename() {

		return null;
	}

	function get_thememode() {

		$selected = $_REQUEST[GD_URLPARAM_THEMEMODE];
		
		if (!$selected || !isset($this->thememodes[$selected])) {
			$selected = $this->get_default_thememodename();
		}

		return $this->thememodes[$selected];
	}

	function get_themestyle() {

		$selected = $_REQUEST[GD_URLPARAM_THEMESTYLE];
		
		if (!$selected || !isset($this->themestyles[$selected])) {
			$selected = $this->get_default_themestylename();
		}

		return $this->themestyles[$selected];
	}
}