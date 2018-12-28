<?php
namespace PoP\Engine\Themes;

abstract class ThemeStyleBase {

	function __construct() {

		$this->get_theme()->add_themestyle($this);
	}

	abstract function get_theme();

	abstract function get_name();
}