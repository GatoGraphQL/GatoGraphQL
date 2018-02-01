<?php
// class PoP_ResourceLoader_JSBundleGroupFileGenerator extends PoP_ResourceLoader_JSBundleFileFileGeneratorBase {
class PoP_ResourceLoader_JSBundleGroupFileGeneratorBase extends PoP_ResourceLoader_BundleFileFileGeneratorBase {

	function get_dir() {

		return parent::get_dir().'/bundlegroups';
	}
	function get_url() {

		return parent::get_url().'/bundlegroups';
	}
}