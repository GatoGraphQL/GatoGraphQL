<?php

class PoPEngine_ThemeModeBase {

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