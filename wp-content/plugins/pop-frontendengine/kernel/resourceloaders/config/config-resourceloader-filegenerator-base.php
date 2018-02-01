<?php
class PoP_ResourceLoader_ConfigFileGeneratorBase extends PoP_Engine_RendererFileGeneratorBase {

	protected function get_base_dir() {

		return POP_RESOURCELOADER_CONTENT_DIR;
	}
	protected function get_base_url() {

		return POP_RESOURCELOADER_CONTENT_URL;
	}

	protected function get_folder_subpath() {

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
}