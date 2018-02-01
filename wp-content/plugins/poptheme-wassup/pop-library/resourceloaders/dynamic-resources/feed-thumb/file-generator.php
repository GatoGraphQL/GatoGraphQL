<?php
class PoPThemeWassup_FeedThumb_FileGenerator extends PoP_Engine_RendererFileGeneratorBase {

	function get_dir() {

		return POPTHEME_WASSUP_THEMECUSTOMIZATION_ASSETDESTINATION_DIR;
	}
	function get_url() {

		return POPTHEME_WASSUP_THEMECUSTOMIZATION_ASSETDESTINATION_URL;
	}

	function get_filename() {

		return 'feed-thumb.css';
	}

	function get_renderer() {

		global $popthemewassup_feedthumb_filerenderer;
		return $popthemewassup_feedthumb_filerenderer;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $popthemewassup_feedthumb_filegenerator;
$popthemewassup_feedthumb_filegenerator = new PoPThemeWassup_FeedThumb_FileGenerator();