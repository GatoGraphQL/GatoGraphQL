<?php

class GD_ThemeStyleBase {

	function __construct() {

		$this->get_theme()->add_themestyle($this);
	}

	function get_theme() {

		return null;
	}

	function get_name() {
		
		return '';
	}
}