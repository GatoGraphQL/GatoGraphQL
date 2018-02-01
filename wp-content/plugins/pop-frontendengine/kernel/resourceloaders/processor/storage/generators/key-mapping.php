<?php
// class PoP_ResourceLoader_KeyMappingStorageFileGenerator extends PoP_Engine_SingleThememodeResourceLoaderFileGeneratorBase {
class PoP_ResourceLoader_KeyMappingStorageFileLocation extends PoP_Engine_ResourceLoaderFileObjectBase {

	function get_filename() {

		return 'key-resourceloader-mapping.json';
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_keymappingstoragefilelocation;
$pop_resourceloader_keymappingstoragefilelocation = new PoP_ResourceLoader_KeyMappingStorageFileLocation();
