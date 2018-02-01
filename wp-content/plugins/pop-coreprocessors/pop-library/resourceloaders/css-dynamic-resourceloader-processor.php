<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_CSS_USERLOGGEDIN', PoP_TemplateIDUtils::get_template_definition('user-loggedin'));

class PoP_CoreProcessors_DynamicCSSResourceLoaderProcessor extends PoP_DynamicCSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_CSS_USERLOGGEDIN,
		);
	}
	
	function get_filename($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_CSS_USERLOGGEDIN:

				global $popcore_userloggedinstyles_filegenerator;
				return $popcore_userloggedinstyles_filegenerator->get_filename();
		}

		return parent::get_filename($resource);
	}
	
	function get_dir($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_CSS_USERLOGGEDIN:

				global $popcore_userloggedinstyles_filegenerator;
				return $popcore_userloggedinstyles_filegenerator->get_dir();
		}
	
		return parent::get_dir($resource);
	}
	
	function get_file_url($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_CSS_USERLOGGEDIN:
				
				global $popcore_userloggedinstyles_filegenerator;
				return $popcore_userloggedinstyles_filegenerator->get_fileurl();
		}

		return parent::get_file_url($resource);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CoreProcessors_DynamicCSSResourceLoaderProcessor();
