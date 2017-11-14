<?php
class PoP_ResourceLoader_BundleGroupFileGenerator extends PoP_ResourceLoader_BundleFileFileGeneratorBase {

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
global $pop_resourceloader_bundlegroupfilegenerator;
$pop_resourceloader_bundlegroupfilegenerator = new PoP_ResourceLoader_BundleGroupFileGenerator();