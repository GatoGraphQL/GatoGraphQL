<?php
class PoP_ResourceLoader_ConfigFileGeneratorBase extends PoP_Frontend_RendererFileGeneratorBase {

	function get_dir() {

		// The configuration depends on the 'theme' and 'thememode', so then store it under these values
		$vars = GD_TemplateManager_Utils::get_vars();
		return POP_FRONTENDENGINE_CONTENT_DIR.'/resourceloader/'.$vars['theme'].'/'.$vars['thememode'];
	}
	function get_url() {

		$vars = GD_TemplateManager_Utils::get_vars();
		return POP_FRONTENDENGINE_CONTENT_URI.'/resourceloader/'.$vars['theme'].'/'.$vars['thememode'];
	}
}