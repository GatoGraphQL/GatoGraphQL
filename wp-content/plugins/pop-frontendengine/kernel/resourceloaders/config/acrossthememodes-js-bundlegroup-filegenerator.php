<?php
class PoP_ResourceLoader_AcrossThememodesJSBundleGroupFileGenerator extends PoP_ResourceLoader_JSBundleGroupFileGeneratorBase {

	protected function across_thememodes() {

		return true;
	}
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_acrossthememodes_jsbundlegroupfilegenerator;
$pop_resourceloader_acrossthememodes_jsbundlegroupfilegenerator = new PoP_ResourceLoader_AcrossThememodesJSBundleGroupFileGenerator();