<?php
class PoP_ResourceLoader_GeneratedFilesStorageFileGenerator extends PoP_Engine_ResourceLoaderFileGeneratorBase {

	function get_filename() {

		// We must create different mapping files depending on if we're adding the CDN resources inside the bundles or not
		// return 'resourceloader-generatedfiles-'.$this->get_scope().'.json';
		return 'resourceloader-generatedfiles.json';
	}

	public function save($generatedfiles) {

		global $pop_engine_filejsonstorage;
		$pop_engine_filejsonstorage->save($this->get_filepath(), $generatedfiles);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_generatedfilesstoragefilegenerator;
$pop_resourceloader_generatedfilesstoragefilegenerator = new PoP_ResourceLoader_GeneratedFilesStorageFileGenerator();
