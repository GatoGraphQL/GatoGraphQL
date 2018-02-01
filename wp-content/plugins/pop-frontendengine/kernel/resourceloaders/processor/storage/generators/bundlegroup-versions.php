<?php
class PoP_ResourceLoader_BundleGroupVersionStorageFileLocation extends PoP_Engine_ResourceLoaderFileObjectBase {

	function get_filename() {

		return 'bundlegroup-versions.json';
	}

	protected function across_thememodes() {

		return true;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_bundlegroupversionstoragefilelocation;
$pop_resourceloader_bundlegroupversionstoragefilelocation = new PoP_ResourceLoader_BundleGroupVersionStorageFileLocation();
