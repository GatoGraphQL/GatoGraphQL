<?php
class PoP_Engine_ResourceLoaderFileGeneratorBase extends PoP_Engine_FileGeneratorBase {

	function get_dir() {

		// We have different bundle mappings for different combinations of theme and thememode,
		// so then store these files accordingly
		$vars = GD_TemplateManager_Utils::get_vars();
		return POP_FRONTENDENGINE_GENERATECACHE_DIR.'/resourceloader/'.$vars['theme'].'/'.$vars['thememode'];
	}

	protected function get_scope() {

		// We must create different mapping files depending on if we're adding the CDN resources inside the bundles or not
		return PoP_Frontend_ServerUtils::bundle_external_files() ? 'global' : 'local';
	}

	public function delete() {

		global $pop_engine_filejsonstorage;
		$pop_engine_filejsonstorage->delete($this->get_filepath());
	}
}
