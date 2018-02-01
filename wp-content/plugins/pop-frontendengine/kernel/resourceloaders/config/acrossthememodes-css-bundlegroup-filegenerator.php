<?php
class PoP_ResourceLoader_AcrossThememodesCSSBundleGroupFileGenerator extends PoP_ResourceLoader_CSSBundleGroupFileGeneratorBase {

	protected function across_thememodes() {

		return true;
	}
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_acrossthememodes_cssbundlegroupfilegenerator;
$pop_resourceloader_acrossthememodes_cssbundlegroupfilegenerator = new PoP_ResourceLoader_AcrossThememodesCSSBundleGroupFileGenerator();