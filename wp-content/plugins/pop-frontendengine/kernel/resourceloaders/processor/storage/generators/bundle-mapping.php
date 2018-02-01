<?php
// class PoP_ResourceLoader_BundleMappingStorageFileGenerator extends PoP_Engine_AcrossThememodeResourceLoaderFileGeneratorBase {
class PoP_ResourceLoader_BundleMappingStorageFileLocation extends PoP_Engine_ResourceLoaderFileObjectBase {

	function get_filename() {

		return 'bundle-resourceloader-mapping.json';
	}

	protected function across_thememodes() {

		return true;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_bundlemappingstoragefilelocation;
$pop_resourceloader_bundlemappingstoragefilelocation = new PoP_ResourceLoader_BundleMappingStorageFileLocation();
