<?php
class PoP_ResourceLoader_BundleGroupVersionStorageFileLocation extends PoP_Engine_ResourceLoaderFileObjectBase {

	public function getFilename(): string {

		return 'bundlegroup-versions.json';
	}

	protected function acrossThememodes() {

		return true;
	}
}

/**
 * Initialize
 */
global $pop_resourceloader_bundlegroupversionstoragefilelocation;
$pop_resourceloader_bundlegroupversionstoragefilelocation = new PoP_ResourceLoader_BundleGroupVersionStorageFileLocation();
