<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_CSS_BACKGROUNDIMAGE', PoP_TemplateIDUtils::get_template_definition('background-image'));
define ('POP_RESOURCELOADER_CSS_FEEDTHUMB', PoP_TemplateIDUtils::get_template_definition('feed-thumb'));

class PoPThemeWassup_DynamicCSSResourceLoaderProcessor extends PoP_DynamicCSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_CSS_BACKGROUNDIMAGE,
			POP_RESOURCELOADER_CSS_FEEDTHUMB,
		);
	}
	
	function get_filename($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_CSS_BACKGROUNDIMAGE:

				global $popthemewassup_backgroundimage_filegenerator;
				return $popthemewassup_backgroundimage_filegenerator->get_filename();

			case POP_RESOURCELOADER_CSS_FEEDTHUMB:

				global $popthemewassup_feedthumb_filegenerator;
				return $popthemewassup_feedthumb_filegenerator->get_filename();
		}

		return parent::get_filename($resource);
	}
	
	function get_dir($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_CSS_BACKGROUNDIMAGE:

				global $popthemewassup_backgroundimage_filegenerator;
				return $popthemewassup_backgroundimage_filegenerator->get_dir();

			case POP_RESOURCELOADER_CSS_FEEDTHUMB:

				global $popthemewassup_feedthumb_filegenerator;
				return $popthemewassup_feedthumb_filegenerator->get_dir();
		}
	
		return parent::get_dir($resource);
	}
	
	function get_file_url($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_CSS_BACKGROUNDIMAGE:
				
				global $popthemewassup_backgroundimage_filegenerator;
				return $popthemewassup_backgroundimage_filegenerator->get_fileurl();

			case POP_RESOURCELOADER_CSS_FEEDTHUMB:
				
				global $popthemewassup_feedthumb_filegenerator;
				return $popthemewassup_feedthumb_filegenerator->get_fileurl();
		}

		return parent::get_file_url($resource);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_DynamicCSSResourceLoaderProcessor();
