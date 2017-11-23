<?php
class PoP_ResourceLoader_CSSBundleFileGenerator extends PoP_ResourceLoader_CSSBundleFileFileGeneratorBase {

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
global $pop_resourceloader_cssbundlefilegenerator;
$pop_resourceloader_cssbundlefilegenerator = new PoP_ResourceLoader_CSSBundleFileGenerator();