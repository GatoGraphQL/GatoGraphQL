<?php
// class PoP_ResourceLoader_BundleGroupMappingStorageFileGenerator extends PoP_Engine_AcrossThememodeResourceLoaderFileGeneratorBase {
class PoP_ResourceLoader_BundleGroupMappingStorageFileLocation extends PoP_Engine_ResourceLoaderFileObjectBase {

	function get_filename() {

		return 'bundlegroup-resourceloader-mapping.json';
	}

	protected function across_thememodes() {

		return true;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_bundlegroupmappingstoragefilelocation;
$pop_resourceloader_bundlegroupmappingstoragefilelocation = new PoP_ResourceLoader_BundleGroupMappingStorageFileLocation();
