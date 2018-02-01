<?php
// class PoP_ResourceLoader_CSSBundleFileGenerator extends PoP_ResourceLoader_CSSBundleFileFileGeneratorBase {
class PoP_ResourceLoader_CSSBundleFileGeneratorBase extends PoP_ResourceLoader_BundleFileFileGeneratorBase {

	function get_dir() {

		return parent::get_dir().'/bundles';
	}
	function get_url() {

		return parent::get_url().'/bundles';
	}
}