<?php
class PoP_ResourceLoader_GeneratedResourcesFileLocation extends PoP_Engine_ResourceLoaderFileObjectBase {

	public function getFilename(): string {

		return 'resourceloader-generatedfiles.json';
	}
}

/**
 * Initialize
 */
global $pop_resourceloader_generatedfilesfilelocation;
$pop_resourceloader_generatedfilesfilelocation = new PoP_ResourceLoader_GeneratedResourcesFileLocation();
