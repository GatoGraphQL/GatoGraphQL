<?php
class PoP_ResourceLoader_ResourcesFileGeneratorBase extends PoP_Engine_RendererFileGeneratorBase {

	protected function across_thememodes() {

		return false;
	}

	// function skip_if_file_exists() {

	// 	// Skip if the bundle/bundleGroup has already been generated! That is because these are shared across thememodes,
	// 	// then no need to re-create the files when running /generate-thememode/ for the 2nd, 3rd, etc rounds
	// 	if ($this->across_thememodes()) {

	// 		return true;
	// 	}

	// 	return parent::skip_if_file_exists();
	// }
	
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

		return $this->get_base_dir().$this->get_folder_subpath();
	}
	function get_url() {

		return $this->get_base_url().$this->get_folder_subpath();
	}

	protected function get_base_dir() {

		// Allow pop-cluster-resourceloader to change the dir to pop-cluster-generatecache/
		return apply_filters(
			'PoP_ResourceLoader_ResourcesFileGeneratorBase:base-dir',
			POP_RESOURCES_DIR,
			$this->across_thememodes()
		);
	}
	protected function get_base_url() {

		// Allow pop-cluster-resourceloader to change the dir to pop-cluster-generatecache/
		return apply_filters(
			'PoP_ResourceLoader_ResourcesFileGeneratorBase:base-url',
			POP_RESOURCES_URL,
			$this->across_thememodes()
		);
	}
}