<?php
namespace PoP\Engine\Themes;

abstract class ThemeModeBase {

	function __construct() {

		$this->get_theme()->add_thememode($this);
	}

	function get_theme() {

		return null;
	}

	function get_name() {
		
		return '';
	}
}