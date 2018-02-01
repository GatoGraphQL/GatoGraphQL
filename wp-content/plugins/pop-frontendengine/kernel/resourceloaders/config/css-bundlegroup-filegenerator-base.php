<?php
// class PoP_ResourceLoader_CSSBundleGroupFileGenerator extends PoP_ResourceLoader_CSSBundleFileFileGeneratorBase {
class PoP_ResourceLoader_CSSBundleGroupFileGeneratorBase extends PoP_ResourceLoader_BundleFileFileGeneratorBase {

	function get_dir() {

		return parent::get_dir().'/bundlegroups';
	}
	function get_url() {

		return parent::get_url().'/bundlegroups';
	}
}