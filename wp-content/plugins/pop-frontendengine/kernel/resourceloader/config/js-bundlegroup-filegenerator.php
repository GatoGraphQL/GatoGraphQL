<?php
class PoP_ResourceLoader_JSBundleGroupFileGenerator extends PoP_ResourceLoader_JSBundleFileFileGeneratorBase {

	function get_dir() {

		return parent::get_dir().'/bundlegroups';//.'/'.$this->get_subfolder();
	}
	function get_url() {

		return parent::get_url().'/bundlegroups';//.'/'.$this->get_subfolder();
	}
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_jsbundlegroupfilegenerator;
$pop_resourceloader_jsbundlegroupfilegenerator = new PoP_ResourceLoader_JSBundleGroupFileGenerator();