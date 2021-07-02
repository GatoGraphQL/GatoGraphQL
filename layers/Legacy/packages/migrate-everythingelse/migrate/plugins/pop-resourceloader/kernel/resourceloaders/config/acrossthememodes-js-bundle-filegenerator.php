<?php
class PoP_ResourceLoader_AcrossThememodesJSBundleFile extends PoP_ResourceLoader_JSBundleFileBase {

	protected function acrossThememodes() {

		return true;
	}
}
    
/**
 * Initialize
 */
global $pop_resourceloader_acrossthememodes_jsbundlefile;
$pop_resourceloader_acrossthememodes_jsbundlefile = new PoP_ResourceLoader_AcrossThememodesJSBundleFile();
