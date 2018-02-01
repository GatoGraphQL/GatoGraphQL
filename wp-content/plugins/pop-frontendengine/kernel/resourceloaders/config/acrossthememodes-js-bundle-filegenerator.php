<?php
class PoP_ResourceLoader_AcrossThememodesJSBundleFileGenerator extends PoP_ResourceLoader_JSBundleFileGeneratorBase {

	protected function across_thememodes() {

		return true;
	}
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_acrossthememodes_jsbundlefilegenerator;
$pop_resourceloader_acrossthememodes_jsbundlefilegenerator = new PoP_ResourceLoader_AcrossThememodesJSBundleFileGenerator();