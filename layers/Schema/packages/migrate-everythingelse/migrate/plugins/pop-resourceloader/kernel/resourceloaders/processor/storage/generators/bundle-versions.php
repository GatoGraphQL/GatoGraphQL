<?php
class PoP_ResourceLoader_BundleVersionStorageFileLocation extends PoP_Engine_ResourceLoaderFileObjectBase {

	public function getFilename(): string {

		return 'bundle-versions.json';
	}

	protected function acrossThememodes() {

		return true;
	}
}

/**
 * Initialize
 */
global $pop_resourceloader_bundleversionstoragefilelocation;
$pop_resourceloader_bundleversionstoragefilelocation = new PoP_ResourceLoader_BundleVersionStorageFileLocation();
