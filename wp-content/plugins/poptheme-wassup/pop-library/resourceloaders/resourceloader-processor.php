<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_CUSTOMFUNCTIONS', PoP_ServerUtils::get_template_definition('custom-functions'));
define ('POP_RESOURCELOADER_CUSTOMPAGESECTIONMANAGER', PoP_ServerUtils::get_template_definition('custom-pagesection-manager'));
define ('POP_RESOURCELOADER_UREAALFUNCTIONS', PoP_ServerUtils::get_template_definition('ure-aal-functions'));
define ('POP_RESOURCELOADER_URECOMMUNITIES', PoP_ServerUtils::get_template_definition('ure-communities'));

class PoPTheme_Wassup_ResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_CUSTOMFUNCTIONS,
			POP_RESOURCELOADER_CUSTOMPAGESECTIONMANAGER,
			POP_RESOURCELOADER_UREAALFUNCTIONS,
			POP_RESOURCELOADER_URECOMMUNITIES,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_CUSTOMFUNCTIONS => 'custom-functions',
			POP_RESOURCELOADER_CUSTOMPAGESECTIONMANAGER => 'custom-pagesection-manager',
			POP_RESOURCELOADER_UREAALFUNCTIONS => 'ure-aal-functions',
			POP_RESOURCELOADER_URECOMMUNITIES => 'ure-communities',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POPTHEME_WASSUP_VERSION;
	}
	
	function get_dir($resource) {

		return POPTHEME_WASSUP_DIR.'/js/libraries';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POPTHEME_WASSUP_URI.'/js/'.$subpath.'libraries';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_CUSTOMFUNCTIONS => array(
				'popCustomFunctions',
			),
			POP_RESOURCELOADER_CUSTOMPAGESECTIONMANAGER => array(
				'popCustomPageSectionManager',
			),
			POP_RESOURCELOADER_UREAALFUNCTIONS => array(
				'popUREAAL',
			),
			POP_RESOURCELOADER_URECOMMUNITIES => array(
				'popUserRole',
			),
		);
		if ($object = $objects[$resource]) {

			return $object;
		}

		return parent::get_jsobjects($resource);
	}
	
	function get_dependencies($resource) {

		$dependencies = parent::get_dependencies($resource);
	
		switch ($resource) {
		
			case POP_RESOURCELOADER_CUSTOMFUNCTIONS:

				// Because it calls Bootstrap function "alert", we must make sure Bootstrap is loaded or it produces a JS error
				// (this happens when the internal/external method call mapping has not been generated)
				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_BOOTSTRAP;
				break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_ResourceLoaderProcessor();
