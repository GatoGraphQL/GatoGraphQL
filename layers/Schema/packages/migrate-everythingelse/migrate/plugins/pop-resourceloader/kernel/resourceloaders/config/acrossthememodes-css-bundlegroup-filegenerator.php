<?php
class PoP_ResourceLoader_AcrossThememodesCSSBundleGroupFile extends PoP_ResourceLoader_CSSBundleGroupFileBase {

	protected function acrossThememodes() {

		return true;
	}
}
    
/**
 * Initialize
 */
global $pop_resourceloader_acrossthememodes_cssbundlegroupfile;
$pop_resourceloader_acrossthememodes_cssbundlegroupfile = new PoP_ResourceLoader_AcrossThememodesCSSBundleGroupFile();
