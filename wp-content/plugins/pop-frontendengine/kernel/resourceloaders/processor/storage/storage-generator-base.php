<?php
class PoP_Engine_ResourceLoaderFileObjectBase  extends PoP_Engine_FileObjectBase {

	protected function across_thememodes() {

		return false;
	}

	protected function get_base_dir() {

		// Allow pop-cluster-resourceloader to change the dir to pop-cluster-generatecache/
		return apply_filters(
			'PoP_Engine_ResourceLoaderFileObjectBase:base-dir',
			POP_FRONTENDENGINE_GENERATECACHE_DIR,
			$this->across_thememodes()
		);
	}
	
	protected function get_folder_subpath() {

		if ($this->across_thememodes()) {

			return '/shared';
		}
		
		$vars = GD_TemplateManager_Utils::get_vars();

		// We must create different mapping files depending on if we're adding the CDN resources inside the bundles or not
		$subfolder = PoP_Frontend_ServerUtils::bundle_external_files() ? 'global' : 'local';
		return '/'.$vars['theme'].'/'.$vars['thememode'].'/'.$subfolder;
	}

	function get_dir() {

		return $this->get_base_dir().'/resourceloader'.$this->get_folder_subpath();
	}
}
