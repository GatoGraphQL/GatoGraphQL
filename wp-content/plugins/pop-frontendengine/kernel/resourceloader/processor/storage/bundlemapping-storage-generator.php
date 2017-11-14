<?php
class PoP_ResourceLoader_BundleMappingStorageFileGenerator extends PoP_Engine_ResourceLoaderFileGeneratorBase {

	function get_filename() {

		// We must create different mapping files depending on if we're adding the CDN resources inside the bundles or not
		return 'resourceloader-bundle-mapping-'.$this->get_scope().'.json';
	}

	public function save($bundle_ids, $bundlegroup_ids, $key_ids) {

		// Get all the .css files from all the plugins
		$entries = array(
			'bundle-ids' => $bundle_ids,
			'bundlegroup-ids' => $bundlegroup_ids,
			'key-ids' => $key_ids,
		);

		global $pop_engine_filejsonstorage;
		$pop_engine_filejsonstorage->save($this->get_filepath(), $entries);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_bundlemappingstoragefilegenerator;
$pop_resourceloader_bundlemappingstoragefilegenerator = new PoP_ResourceLoader_BundleMappingStorageFileGenerator();
