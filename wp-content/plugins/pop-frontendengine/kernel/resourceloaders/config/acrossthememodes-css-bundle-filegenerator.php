<?php
class PoP_ResourceLoader_AcrossThememodesCSSBundleFileGenerator extends PoP_ResourceLoader_CSSBundleFileGeneratorBase {

	protected function across_thememodes() {

		return true;
	}
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_acrossthememodes_cssbundlefilegenerator;
$pop_resourceloader_acrossthememodes_cssbundlefilegenerator = new PoP_ResourceLoader_AcrossThememodesCSSBundleFileGenerator();