<?php
class PoP_ResourceLoader_KeyMappingStorageFileLocation extends PoP_Engine_ResourceLoaderFileObjectBase {

	public function getFilename(): string {

		return 'key-resourceloader-mapping.json';
	}
}

/**
 * Initialize
 */
global $pop_resourceloader_keymappingstoragefilelocation;
$pop_resourceloader_keymappingstoragefilelocation = new PoP_ResourceLoader_KeyMappingStorageFileLocation();
