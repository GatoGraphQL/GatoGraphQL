<?php
class PoP_ResourceLoader_AbbreviationsStorageFileGenerator extends PoP_Engine_FileGeneratorBase {

	function get_dir() {

		// We have different bundle mappings for different combinations of theme and thememode,
		// so then store these files accordingly
		$vars = GD_TemplateManager_Utils::get_vars();
		return POP_FRONTENDENGINE_GENERATECACHE_DIR.'/resourceloader/'.$vars['theme'].'/'.$vars['thememode'];
	}

	function get_filename() {

		return 'resourceloader-bundle-mapping.json';
	}

	public function save($bundle_ids, $bundlegroup_ids, $key_ids) {

		// Get all the .css files from all the plugins
		$abbreviations = array(
			'bundle-ids' => $bundle_ids,
			'bundlegroup-ids' => $bundlegroup_ids,
			'key-ids' => $key_ids,
		);

		global $pop_engine_filejsonstorage;
		$pop_engine_filejsonstorage->save($this->get_filepath(), $abbreviations);
	}

	public function delete() {

		global $pop_engine_filejsonstorage;
		$pop_engine_filejsonstorage->delete($this->get_filepath());
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_abbreviationsstorage_generator;
$pop_resourceloader_abbreviationsstorage_generator = new PoP_ResourceLoader_AbbreviationsStorageFileGenerator();