<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_COMPATIBILITY', PoP_TemplateIDUtils::get_template_definition('compatibility'));
define ('POP_RESOURCELOADER_HELPERSHANDLEBARS', PoP_TemplateIDUtils::get_template_definition('helpers.handlebars'));
define ('POP_RESOURCELOADER_HISTORY', PoP_TemplateIDUtils::get_template_definition('history'));
define ('POP_RESOURCELOADER_INTERCEPTORS', PoP_TemplateIDUtils::get_template_definition('interceptors'));
define ('POP_RESOURCELOADER_JSLIBRARYMANAGER', PoP_TemplateIDUtils::get_template_definition('jslibrary-manager'));
define ('POP_RESOURCELOADER_CODESPLITJSLIBRARYMANAGER', PoP_TemplateIDUtils::get_template_definition('codesplit-jslibrary-manager'));
define ('POP_RESOURCELOADER_JSRUNTIMEMANAGER', PoP_TemplateIDUtils::get_template_definition('jsruntime-manager'));
define ('POP_RESOURCELOADER_PAGESECTIONMANAGER', PoP_TemplateIDUtils::get_template_definition('pagesection-manager'));
define ('POP_RESOURCELOADER_POPMANAGER', PoP_TemplateIDUtils::get_template_definition('pop-manager'));
define ('POP_RESOURCELOADER_TOPLEVELJSON', PoP_TemplateIDUtils::get_template_definition('topleveljson'));
define ('POP_RESOURCELOADER_RESOURCELOADER', PoP_TemplateIDUtils::get_template_definition('resourceloader'));
define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG', PoP_TemplateIDUtils::get_template_definition('resourceloader-config'));
define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES', PoP_TemplateIDUtils::get_template_definition('resourceloader-config-resources'));
define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES', PoP_TemplateIDUtils::get_template_definition('resourceloader-config-initialresources'));
define ('POP_RESOURCELOADER_POPUTILS', PoP_TemplateIDUtils::get_template_definition('pop-utils'));
define ('POP_RESOURCELOADER_UTILS', PoP_TemplateIDUtils::get_template_definition('utils'));
// define ('POP_RESOURCELOADER_INLINEUTILS', PoP_TemplateIDUtils::get_template_definition('utils-inline'));

class PoP_FrontEnd_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_COMPATIBILITY,
			POP_RESOURCELOADER_HELPERSHANDLEBARS,
			POP_RESOURCELOADER_HISTORY,
			POP_RESOURCELOADER_INTERCEPTORS,
			POP_RESOURCELOADER_JSLIBRARYMANAGER,
			POP_RESOURCELOADER_CODESPLITJSLIBRARYMANAGER,
			POP_RESOURCELOADER_JSRUNTIMEMANAGER,
			POP_RESOURCELOADER_PAGESECTIONMANAGER,
			POP_RESOURCELOADER_POPMANAGER,
			POP_RESOURCELOADER_TOPLEVELJSON,
			POP_RESOURCELOADER_RESOURCELOADER,
			POP_RESOURCELOADER_RESOURCELOADERCONFIG,
			POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES,
			POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES,
			POP_RESOURCELOADER_POPUTILS,
			POP_RESOURCELOADER_UTILS,
			// POP_RESOURCELOADER_INLINEUTILS,
		);
	}
	
	function get_filename($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
				
				global $pop_resourceloader_configfile_generator;
				return $pop_resourceloader_configfile_generator->get_filename();
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:

				global $pop_resourceloader_resources_configfile_generator;
				return $pop_resourceloader_resources_configfile_generator->get_filename();
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

				global $pop_resourceloader_initialresources_configfile_generator;
				return $pop_resourceloader_initialresources_configfile_generator->get_filename();
		}
	
		$filenames = array(
			POP_RESOURCELOADER_COMPATIBILITY => 'compatibility',
			POP_RESOURCELOADER_HELPERSHANDLEBARS => 'helpers.handlebars',
			POP_RESOURCELOADER_HISTORY => 'history',
			POP_RESOURCELOADER_INTERCEPTORS => 'interceptors',
			POP_RESOURCELOADER_JSLIBRARYMANAGER => 'jslibrary-manager',
			POP_RESOURCELOADER_CODESPLITJSLIBRARYMANAGER => 'codesplit-jslibrary-manager',
			POP_RESOURCELOADER_JSRUNTIMEMANAGER => 'jsruntime-manager',
			POP_RESOURCELOADER_PAGESECTIONMANAGER => 'pagesection-manager',
			POP_RESOURCELOADER_POPMANAGER => 'pop-manager',
			POP_RESOURCELOADER_TOPLEVELJSON => 'topleveljson',
			POP_RESOURCELOADER_RESOURCELOADER => 'resourceloader',
			POP_RESOURCELOADER_POPUTILS => 'pop-utils',
			POP_RESOURCELOADER_UTILS => 'utils',
			// POP_RESOURCELOADER_INLINEUTILS => 'utils-inline',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	// function inline($resource) {

	// 	switch ($resource) {

	// 		case POP_RESOURCELOADER_INLINEUTILS:
				
	// 			return true;
	// 	}
	
	// 	return parent::inline($resource);
	// }
	
	function get_version($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:
				
				// This script file is dynamically generated getting data from all over the website, so its version depend on the website version
				return pop_version();
		}
	
		return POP_FRONTENDENGINE_VERSION;
	}
	
	function get_suffix($resource) {
	
		switch ($resource) {

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:
				
				// This script file is dynamically generated getting data from all over the website, so its version depend on the website version
				return '';
		}
		return parent::get_suffix($resource);
	}
	
	function get_dir($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
				
				global $pop_resourceloader_configfile_generator;
				return $pop_resourceloader_configfile_generator->get_dir();
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:

				global $pop_resourceloader_resources_configfile_generator;
				return $pop_resourceloader_resources_configfile_generator->get_dir();
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

				global $pop_resourceloader_initialresources_configfile_generator;
				return $pop_resourceloader_initialresources_configfile_generator->get_dir();
		}
	
		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_FRONTENDENGINE_DIR.'/js/'.$subpath.'libraries';
	}
	
	function get_asset_path($resource) {
	
		switch ($resource) {

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

				return parent::get_asset_path($resource);
		}

		return POP_FRONTENDENGINE_DIR.'/js/libraries/'.$this->get_filename($resource).'.js';
	}
		
	function extract_mapping($resource) {

		// No need to extract the mapping from this file (also, it doesn't exist under that get_dir() folder)
		switch ($resource) {
			
			case POP_RESOURCELOADER_COMPATIBILITY:
			case POP_RESOURCELOADER_HELPERSHANDLEBARS:
			case POP_RESOURCELOADER_UTILS:
			// case POP_RESOURCELOADER_INLINEUTILS:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:
				
				return false;
		}
	
		return parent::extract_mapping($resource);
	}
	
	function get_file_url($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
				
				global $pop_resourceloader_configfile_generator;
				return $pop_resourceloader_configfile_generator->get_fileurl();
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:

				global $pop_resourceloader_resources_configfile_generator;
				return $pop_resourceloader_resources_configfile_generator->get_fileurl();
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

				global $pop_resourceloader_initialresources_configfile_generator;
				return $pop_resourceloader_initialresources_configfile_generator->get_fileurl();
		}

		return parent::get_file_url($resource);
	}
	
	// function get_htmltag_attributes($resource) {

	// 	switch ($resource) {
			
	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:

	// 			return "defer='defer'";

	// 	}

	// 	return parent::get_htmltag_attributes($resource);
	// }
	
	function is_defer($resource) {

		switch ($resource) {
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:

				return true;
		}

		return parent::is_defer($resource);
	}
	
	function can_bundle($resource) {

		switch ($resource) {
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:

				return false;
		}

		return parent::can_bundle($resource);
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_FRONTENDENGINE_URI.'/js/'.$subpath.'libraries';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_HISTORY => array(
				'popBrowserHistory',
			),
			POP_RESOURCELOADER_INTERCEPTORS => array(
				'popURLInterceptors',
			),
			POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
				'popJSLibraryManager',
			),
			POP_RESOURCELOADER_CODESPLITJSLIBRARYMANAGER => array(
				'popCodeSplitJSLibraryManager',
			),
			POP_RESOURCELOADER_JSRUNTIMEMANAGER => array(
				'popJSRuntimeManager',
			),
			POP_RESOURCELOADER_PAGESECTIONMANAGER => array(
				'popPageSectionManager',
			),
			POP_RESOURCELOADER_RESOURCELOADER => array(
				'popResourceLoader',
			),
			POP_RESOURCELOADER_POPMANAGER => array(
				'popManager',
			),
			POP_RESOURCELOADER_TOPLEVELJSON => array(
				'popTopLevelJSON',
			),
			POP_RESOURCELOADER_POPUTILS => array(
				'popUtils',
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

			case POP_RESOURCELOADER_HELPERSHANDLEBARS:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_HANDLEBARS/* => array()*/;
				break;

			case POP_RESOURCELOADER_POPMANAGER:

				// PoP Manager is a special case: it's the one library that, we are sure, will always be executed
				// So, inject the dependencies to this resource, to make sure they will always be loaded
				// All templates depend on the handlebars runtime. Allow plugins to add their own dependencies
				$manager_dependencies = array(
					POP_RESOURCELOADER_COMPATIBILITY,
					POP_RESOURCELOADER_UTILS,
					// POP_RESOURCELOADER_INLINEUTILS,
					POP_RESOURCELOADER_POPUTILS,
					POP_RESOURCELOADER_JSLIBRARYMANAGER,
					POP_RESOURCELOADER_CODESPLITJSLIBRARYMANAGER,

					// The resources below are not strictly needed to be added as dependencies, since they are mapped inside popManager.init internal/external method calls
					// However, if the mapping has not been generated, then that dependency will fail.
					// Just to be sure, add them as dependencies too
					POP_RESOURCELOADER_HISTORY,
					POP_RESOURCELOADER_INTERCEPTORS,
					POP_RESOURCELOADER_JSRUNTIMEMANAGER,
					POP_RESOURCELOADER_PAGESECTIONMANAGER,
					
					// We can add the Resource Loader as a dependency here (even if not referenced in popManager),
					// because if we are not doing code-splitting, then this whole resource loading code
					// will never get executed
					POP_RESOURCELOADER_RESOURCELOADER,
					POP_RESOURCELOADER_RESOURCELOADERCONFIG,
				);

				// Hook in the sitemapping/settings values into the JSON
				// Comment Leo 19/11/2017: if we enable the "config" param, then add this resource always
				// (Otherwise it fails because the configuration is cached but the list of modules to load is different)
				// If not, then add it if we are generating the resources on runtime
				if (PoP_ServerUtils::enable_config_by_params() || PoP_Frontend_ServerUtils::generate_resources_on_runtime()) {

					$manager_dependencies[] = POP_RESOURCELOADER_TOPLEVELJSON;
				}

				// Load the ResourceLoader Config files
				$manager_dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES;

				// Comment Leo 20/11/2017: since making the backgroundLoad execute in window.addEventListener('load', function() {,
				// we can just wait to load resource.js, so no need for initialresources.js anymore
				// Add the backgroundLoad resources from the beginning, so we already have the mapping with the resources for these URL, which will be fetched immediately when loading the website
				// $manager_dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES;

				if ($manager_dependencies = apply_filters(
					'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
					$manager_dependencies
				)) {
					$dependencies = array_merge(
						$dependencies,
						$manager_dependencies
					);
				}
				break;
		
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:

				$dependencies[] = POP_RESOURCELOADER_RESOURCELOADER;
				break;

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

				$dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG;
				break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_FrontEnd_ResourceLoaderProcessor();
