<?php
namespace PoP\Engine\Themes;

abstract class ThemeModeBase {

	function __construct() {

		$this->get_theme()->add_thememode($this);
	}

	abstract function get_theme();

	abstract function get_name();
}