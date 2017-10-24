<?php
class PoP_ResourceLoader_ResourcesConfigFileGeneratorBase extends PoP_ResourceLoader_ConfigFileGeneratorBase {

	function get_dir() {

		return parent::get_dir().'/resources';
	}
	function get_url() {

		return parent::get_url().'/resources';
	}
}