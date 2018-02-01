<?php
class PoPThemeWassup_MultidomainStyles_FileGenerator extends PoP_Engine_RendererFileGeneratorBase {

	function get_dir() {

		return POPTHEME_WASSUP_THEMECUSTOMIZATION_ASSETDESTINATION_DIR;
	}
	function get_url() {

		return POPTHEME_WASSUP_THEMECUSTOMIZATION_ASSETDESTINATION_URL;
	}

	function get_filename() {

		return 'multidomain.css';
	}

	function get_renderer() {

		global $popthemewassup_multidomainstyles_filerenderer;
		return $popthemewassup_multidomainstyles_filerenderer;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $popthemewassup_multidomainstyles_filegenerator;
$popthemewassup_multidomainstyles_filegenerator = new PoPThemeWassup_MultidomainStyles_FileGenerator();