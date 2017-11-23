<?php
class PoP_ResourceLoader_CSSBundleGroupFileGenerator extends PoP_ResourceLoader_CSSBundleFileFileGeneratorBase {

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
global $pop_resourceloader_cssbundlegroupfilegenerator;
$pop_resourceloader_cssbundlegroupfilegenerator = new PoP_ResourceLoader_CSSBundleGroupFileGenerator();