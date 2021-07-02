<?php
class PoP_ResourceLoader_AcrossThememodesJSBundleGroupFile extends PoP_ResourceLoader_JSBundleGroupFileBase {

	protected function acrossThememodes() {

		return true;
	}
}
    
/**
 * Initialize
 */
global $pop_resourceloader_acrossthememodes_jsbundlegroupfile;
$pop_resourceloader_acrossthememodes_jsbundlegroupfile = new PoP_ResourceLoader_AcrossThememodesJSBundleGroupFile();
