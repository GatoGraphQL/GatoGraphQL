<?php
class PoP_ResourceLoader_BundleGroupMappingStorageFileLocation extends PoP_Engine_ResourceLoaderFileObjectBase {

	public function getFilename(): string {

		return 'bundlegroup-resourceloader-mapping.json';
	}

	protected function acrossThememodes() {

		return true;
	}
}

/**
 * Initialize
 */
global $pop_resourceloader_bundlegroupmappingstoragefilelocation;
$pop_resourceloader_bundlegroupmappingstoragefilelocation = new PoP_ResourceLoader_BundleGroupMappingStorageFileLocation();
