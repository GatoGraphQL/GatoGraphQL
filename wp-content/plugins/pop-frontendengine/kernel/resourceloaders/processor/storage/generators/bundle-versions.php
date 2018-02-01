<?php
class PoP_ResourceLoader_BundleVersionStorageFileLocation extends PoP_Engine_ResourceLoaderFileObjectBase {

	function get_filename() {

		return 'bundle-versions.json';
	}

	protected function across_thememodes() {

		return true;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_bundleversionstoragefilelocation;
$pop_resourceloader_bundleversionstoragefilelocation = new PoP_ResourceLoader_BundleVersionStorageFileLocation();
