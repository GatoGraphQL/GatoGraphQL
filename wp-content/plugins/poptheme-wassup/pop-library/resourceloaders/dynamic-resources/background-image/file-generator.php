<?php
class PoPThemeWassup_BackgroundImage_FileGenerator extends PoP_Engine_RendererFileGeneratorBase {

	function get_dir() {

		return POPTHEME_WASSUP_THEMECUSTOMIZATION_ASSETDESTINATION_DIR;
	}
	function get_url() {

		return POPTHEME_WASSUP_THEMECUSTOMIZATION_ASSETDESTINATION_URL;
	}

	function get_filename() {

		return 'background-image.css';
	}

	function get_renderer() {

		global $popthemewassup_backgroundimage_filerenderer;
		return $popthemewassup_backgroundimage_filerenderer;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $popthemewassup_backgroundimage_filegenerator;
$popthemewassup_backgroundimage_filegenerator = new PoPThemeWassup_BackgroundImage_FileGenerator();