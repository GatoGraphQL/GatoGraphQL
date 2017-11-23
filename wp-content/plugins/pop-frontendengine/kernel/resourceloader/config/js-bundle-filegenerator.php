<?php
class PoP_ResourceLoader_JSBundleFileGenerator extends PoP_ResourceLoader_JSBundleFileFileGeneratorBase {

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
global $pop_resourceloader_jsbundlefilegenerator;
$pop_resourceloader_jsbundlefilegenerator = new PoP_ResourceLoader_JSBundleFileGenerator();