<?php
class PoP_WebPlatform_ResourceLoaderMappingFile extends \PoP\FileStore\File\AbstractFile {

	public function getDir(): string {

		return POP_RESOURCELOADER_BUILD_DIR;
	}

	public function getFilename(): string {

		return 'resourceloader-mapping.json';
	}
}

/**
 * Initialize
 */
global $pop_webplatform_resourceloader_mappingfile;
$pop_webplatform_resourceloader_mappingfile = new PoP_WebPlatform_ResourceLoaderMappingFile();
