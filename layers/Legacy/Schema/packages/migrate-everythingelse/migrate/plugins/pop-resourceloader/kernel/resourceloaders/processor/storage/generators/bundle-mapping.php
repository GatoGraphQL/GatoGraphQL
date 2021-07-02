<?php
class PoP_ResourceLoader_BundleMappingStorageFileLocation extends PoP_Engine_ResourceLoaderFileObjectBase {

	public function getFilename(): string {

		return 'bundle-resourceloader-mapping.json';
	}

	protected function acrossThememodes() {

		return true;
	}
}

/**
 * Initialize
 */
global $pop_resourceloader_bundlemappingstoragefilelocation;
$pop_resourceloader_bundlemappingstoragefilelocation = new PoP_ResourceLoader_BundleMappingStorageFileLocation();
