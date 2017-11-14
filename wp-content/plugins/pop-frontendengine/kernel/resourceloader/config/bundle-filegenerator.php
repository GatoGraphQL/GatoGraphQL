<?php
class PoP_ResourceLoader_BundleFileGenerator extends PoP_ResourceLoader_BundleFileFileGeneratorBase {

	function get_dir() {

		return parent::get_dir().'/bundles';//.'/'.$this->get_subfolder();
	}
	function get_url() {

		return parent::get_url().'/bundles';//.'/'.$this->get_subfolder();
	}
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_bundlefilegenerator;
$pop_resourceloader_bundlefilegenerator = new PoP_ResourceLoader_BundleFileGenerator();