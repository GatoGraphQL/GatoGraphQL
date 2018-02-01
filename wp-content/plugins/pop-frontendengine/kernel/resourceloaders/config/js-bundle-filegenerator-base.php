<?php
// class PoP_ResourceLoader_JSBundleFileGenerator extends PoP_ResourceLoader_JSBundleFileFileGeneratorBase {
class PoP_ResourceLoader_JSBundleFileGeneratorBase extends PoP_ResourceLoader_BundleFileFileGeneratorBase {

	function get_dir() {

		return parent::get_dir().'/bundles';
	}
	function get_url() {

		return parent::get_url().'/bundles';
	}
}