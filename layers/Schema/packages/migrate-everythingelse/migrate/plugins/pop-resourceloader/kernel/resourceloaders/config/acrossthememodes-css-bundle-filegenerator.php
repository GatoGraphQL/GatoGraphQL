<?php
class PoP_ResourceLoader_AcrossThememodesCSSBundleFile extends PoP_ResourceLoader_CSSBundleFileBase {

	protected function acrossThememodes() {

		return true;
	}
}
    
/**
 * Initialize
 */
global $pop_resourceloader_acrossthememodes_cssbundlefile;
$pop_resourceloader_acrossthememodes_cssbundlefile = new PoP_ResourceLoader_AcrossThememodesCSSBundleFile();
