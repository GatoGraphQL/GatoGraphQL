<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_COMPATIBILITY', PoP_ServerUtils::get_template_definition('compatibility'));
define ('POP_RESOURCELOADER_HELPERSHANDLEBARS', PoP_ServerUtils::get_template_definition('helpers.handlebars'));
define ('POP_RESOURCELOADER_HISTORY', PoP_ServerUtils::get_template_definition('history'));
define ('POP_RESOURCELOADER_INTERCEPTORS', PoP_ServerUtils::get_template_definition('interceptors'));
define ('POP_RESOURCELOADER_JSLIBRARYMANAGER', PoP_ServerUtils::get_template_definition('jslibrary-manager'));
define ('POP_RESOURCELOADER_JSRUNTIMEMANAGER', PoP_ServerUtils::get_template_definition('jsruntime-manager'));
define ('POP_RESOURCELOADER_PAGESECTIONMANAGER', PoP_ServerUtils::get_template_definition('pagesection-manager'));
define ('POP_RESOURCELOADER_POPMANAGER', PoP_ServerUtils::get_template_definition('pop-manager'));
define ('POP_RESOURCELOADER_TOPLEVELJSON', PoP_ServerUtils::get_template_definition('topleveljson'));
define ('POP_RESOURCELOADER_RESOURCELOADER', PoP_ServerUtils::get_template_definition('resourceloader'));
define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG', PoP_ServerUtils::get_template_definition('resourceloader-config'));
define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES', PoP_ServerUtils::get_template_definition('resourceloader-config-resources'));
define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES', PoP_ServerUtils::get_template_definition('resourceloader-config-initialresources'));
// define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTRESOURCES', PoP_ServerUtils::get_template_definition('resourceloader-config-defaultresources'));
// define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_HOMEDEFAULTRESOURCES', PoP_ServerUtils::get_template_definition('resourceloader-config-homedefaultresources'));
// define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_PAGEDEFAULTRESOURCES', PoP_ServerUtils::get_template_definition('resourceloader-config-pagedefaultresources'));
// define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_SINGLEDEFAULTRESOURCES', PoP_ServerUtils::get_template_definition('resourceloader-config-singledefaultresources'));
// define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_AUTHORDEFAULTRESOURCES', PoP_ServerUtils::get_template_definition('resourceloader-config-authordefaultresources'));
// define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_TAGDEFAULTRESOURCES', PoP_ServerUtils::get_template_definition('resourceloader-config-tagdefaultresources'));
// define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTDELTARESOURCES', PoP_ServerUtils::get_template_definition('resourceloader-config-defaultdeltaresources'));
define ('POP_RESOURCELOADER_POPUTILS', PoP_ServerUtils::get_template_definition('pop-utils'));
define ('POP_RESOURCELOADER_UTILS', PoP_ServerUtils::get_template_definition('utils'));

class PoP_FrontEnd_ResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_COMPATIBILITY,
			POP_RESOURCELOADER_HELPERSHANDLEBARS,
			POP_RESOURCELOADER_HISTORY,
			POP_RESOURCELOADER_INTERCEPTORS,
			POP_RESOURCELOADER_JSLIBRARYMANAGER,
			POP_RESOURCELOADER_JSRUNTIMEMANAGER,
			POP_RESOURCELOADER_PAGESECTIONMANAGER,
			POP_RESOURCELOADER_POPMANAGER,
			POP_RESOURCELOADER_TOPLEVELJSON,
			POP_RESOURCELOADER_RESOURCELOADER,
			POP_RESOURCELOADER_RESOURCELOADERCONFIG,
			POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES,
			POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES,
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTRESOURCES,
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_HOMEDEFAULTRESOURCES,
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_PAGEDEFAULTRESOURCES,
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_SINGLEDEFAULTRESOURCES,
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_AUTHORDEFAULTRESOURCES,
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_TAGDEFAULTRESOURCES,
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTDELTARESOURCES,
			POP_RESOURCELOADER_POPUTILS,
			POP_RESOURCELOADER_UTILS,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_COMPATIBILITY => 'compatibility',
			POP_RESOURCELOADER_HELPERSHANDLEBARS => 'helpers.handlebars',
			POP_RESOURCELOADER_HISTORY => 'history',
			POP_RESOURCELOADER_INTERCEPTORS => 'interceptors',
			POP_RESOURCELOADER_JSLIBRARYMANAGER => 'jslibrary-manager',
			POP_RESOURCELOADER_JSRUNTIMEMANAGER => 'jsruntime-manager',
			POP_RESOURCELOADER_PAGESECTIONMANAGER => 'pagesection-manager',
			POP_RESOURCELOADER_POPMANAGER => 'pop-manager',
			POP_RESOURCELOADER_TOPLEVELJSON => 'topleveljson',
			POP_RESOURCELOADER_RESOURCELOADER => 'resourceloader',
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG => 'resourceloader-config',
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES => 'resourceloader-config-resources',
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES => 'resourceloader-config-initialresources',
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTRESOURCES => 'resourceloader-config-defaultresources',
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_HOMEDEFAULTRESOURCES => 'resourceloader-config-homedefaultresources',
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_PAGEDEFAULTRESOURCES => 'resourceloader-config-pagedefaultresources',
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_SINGLEDEFAULTRESOURCES => 'resourceloader-config-singledefaultresources',
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_AUTHORDEFAULTRESOURCES => 'resourceloader-config-authordefaultresources',
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_TAGDEFAULTRESOURCES => 'resourceloader-config-tagdefaultresources',
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTDELTARESOURCES => 'resourceloader-config-defaultdeltaresources',
			POP_RESOURCELOADER_POPUTILS => 'pop-utils',
			POP_RESOURCELOADER_UTILS => 'utils',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_HOMEDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_PAGEDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_SINGLEDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_AUTHORDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_TAGDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTDELTARESOURCES:
				
				// This script file is dynamically generated getting data from all over the website, so its version depend on the website version
				return pop_version();
		}
	
		return POP_FRONTENDENGINE_VERSION;
	}
	
	function get_dir($resource) {
	
		return POP_FRONTENDENGINE_DIR.'/js/libraries';
	}
		
	function extract_mapping($resource) {

		// No need to extract the mapping from this file (also, it doesn't exist under that get_dir() folder)
		switch ($resource) {
			
			case POP_RESOURCELOADER_COMPATIBILITY:
			case POP_RESOURCELOADER_HELPERSHANDLEBARS:
			case POP_RESOURCELOADER_UTILS:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_HOMEDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_PAGEDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_SINGLEDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_AUTHORDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_TAGDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTDELTARESOURCES:
				
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
			
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTRESOURCES:

			// 	global $pop_resourceloader_defaultresources_configfile_generator;
			// 	return $pop_resourceloader_defaultresources_configfile_generator->get_fileurl();
			
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_HOMEDEFAULTRESOURCES:

			// 	global $pop_resourceloader_homedefaultresources_configfile_generator;
			// 	return $pop_resourceloader_homedefaultresources_configfile_generator->get_fileurl();
			
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_PAGEDEFAULTRESOURCES:

			// 	global $pop_resourceloader_pagedefaultresources_configfile_generator;
			// 	return $pop_resourceloader_pagedefaultresources_configfile_generator->get_fileurl();
			
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_SINGLEDEFAULTRESOURCES:

			// 	global $pop_resourceloader_singledefaultresources_configfile_generator;
			// 	return $pop_resourceloader_singledefaultresources_configfile_generator->get_fileurl();
			
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_AUTHORDEFAULTRESOURCES:

			// 	global $pop_resourceloader_authordefaultresources_configfile_generator;
			// 	return $pop_resourceloader_authordefaultresources_configfile_generator->get_fileurl();
			
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_TAGDEFAULTRESOURCES:

			// 	global $pop_resourceloader_tagdefaultresources_configfile_generator;
			// 	return $pop_resourceloader_tagdefaultresources_configfile_generator->get_fileurl();
			
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTDELTARESOURCES:

			// 	// Watch out! We are adding #defer to add the 'defer' attribute to the script tag
			// 	// Taken from https://stackoverflow.com/questions/18944027/how-do-i-defer-or-async-this-wordpress-javascript-snippet-to-load-lastly-for-fas
			// 	global $pop_resourceloader_defaultdeltaresources_configfile_generator;
			// 	return $pop_resourceloader_defaultdeltaresources_configfile_generator->get_fileurl();
		}

		return parent::get_file_url($resource);
	}
	
	function get_htmltag_attributes($resource) {

		switch ($resource) {
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:

				// return POP_HTMLATTRIBUTES_DEFER;
				return "defer='defer'";
			
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTDELTARESOURCES:

			// 	return "defer='defer'";

			// // Page default is needed for the backgroundLoad loaded pages (eg: initial-frames/),
			// // so then do not defer
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_HOMEDEFAULTRESOURCES:
			// // case POP_RESOURCELOADER_RESOURCELOADERCONFIG_PAGEDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_SINGLEDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_AUTHORDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_TAGDEFAULTRESOURCES:

			// 	return "defer='defer'";

			// // case POP_RESOURCELOADER_RESOURCELOADERCONFIG_HOMEDEFAULTRESOURCES:
			// // case POP_RESOURCELOADER_RESOURCELOADERCONFIG_PAGEDEFAULTRESOURCES:
			// // case POP_RESOURCELOADER_RESOURCELOADERCONFIG_SINGLEDEFAULTRESOURCES:
			// // case POP_RESOURCELOADER_RESOURCELOADERCONFIG_AUTHORDEFAULTRESOURCES:
			// // case POP_RESOURCELOADER_RESOURCELOADERCONFIG_TAGDEFAULTRESOURCES:

			// // 	// Make them deferred if they are not the script needed for the current page
			// // 	$vars = GD_TemplateManager_Utils::get_vars();
			// // 	$hierarchy_resources = array(
			// // 		'home' => POP_RESOURCELOADER_RESOURCELOADERCONFIG_HOMEDEFAULTRESOURCES,
			// // 		'page' => POP_RESOURCELOADER_RESOURCELOADERCONFIG_PAGEDEFAULTRESOURCES,
			// // 		'single' => POP_RESOURCELOADER_RESOURCELOADERCONFIG_SINGLEDEFAULTRESOURCES,
			// // 		'author' => POP_RESOURCELOADER_RESOURCELOADERCONFIG_AUTHORDEFAULTRESOURCES,
			// // 		'tag' => POP_RESOURCELOADER_RESOURCELOADERCONFIG_TAGDEFAULTRESOURCES,
			// // 	);
			// // 	$hierarchy = $vars['global-state']['hierarchy'];
			// // 	if ($hierarchy_resources[$hierarchy] != $resource) {

			// // 		return "defer='defer'";
			// // 	}
			// // 	break;

		}

		return parent::get_htmltag_attributes($resource);
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
					// POP_RESOURCELOADER_POPUTILS,
					// The JS Library Manager is already included in the parent object
					// POP_RESOURCELOADER_JSLIBRARYMANAGER,

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
				if (PoP_Frontend_ServerUtils::generate_resources_on_runtime()) {

					$manager_dependencies[] = POP_RESOURCELOADER_TOPLEVELJSON;
				}

				// Check what strategy to use to load the ResourceLoader Config files
				// if (PoP_Frontend_ServerUtils::use_codesplitting_fastboot()) {

				// 	// Comment Leo 08/10/2017: instead of loading the full default-config.js, load the smaller homedefault-config.js, pagedefault-config.js, etc,
				// 	// and then make them defer if they are not the ones needed for the current page
				// 	// $manager_dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTRESOURCES;
				// 	$manager_dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG_HOMEDEFAULTRESOURCES;
				// 	$manager_dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG_PAGEDEFAULTRESOURCES;
				// 	$manager_dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG_SINGLEDEFAULTRESOURCES;
				// 	$manager_dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG_AUTHORDEFAULTRESOURCES;
				// 	$manager_dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG_TAGDEFAULTRESOURCES;
				// 	$manager_dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTDELTARESOURCES;
				// }
				// else {
				$manager_dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES;

				// Add the backgroundLoad resources from the beginning, so we already have the mapping with the resources for these URL, which will be fetched immediately when loading the website
				$manager_dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES;
				// }
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

			case POP_RESOURCELOADER_RESOURCELOADER:

				$dependencies[] = POP_RESOURCELOADER_JSLIBRARYMANAGER;
				break;
		
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:

				$dependencies[] = POP_RESOURCELOADER_RESOURCELOADER;
				break;

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_HOMEDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_PAGEDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_SINGLEDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_AUTHORDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_TAGDEFAULTRESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_DEFAULTDELTARESOURCES:

				$dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG;
				break;
		}

		return $dependencies;
	}
	
	// function get_external_method_calls($resource) {
	
	// 	switch ($resource) {

	// 		case POP_RESOURCELOADER_HELPERSHANDLEBARS:

	// 			return array(
	// 				'destroyUrl' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'getDestroyUrl',
	// 					),
	// 				),
	// 				'ifget' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'withget' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'expandJSKeys',
	// 					),
	// 				),
	// 				'generateId' => array(
	// 					POP_RESOURCELOADER_JSRUNTIMEMANAGER => array(
	// 						'setBlockURL',
	// 						'addTemplateId',
	// 					),
	// 				),
	// 				'lastGeneratedId' => array(
	// 					POP_RESOURCELOADER_JSRUNTIMEMANAGER => array(
	// 						'getLastGeneratedId',
	// 					),
	// 				),
	// 				'applyLightTemplate' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'getHtml',
	// 					),
	// 				),
	// 				'enterModule' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'getRuntimeConfiguration',
	// 						'expandJSKeys',
	// 						'getItemObject',
	// 						'overrideFromItemObject',
	// 						'replaceFromItemObject',
	// 						'getHtml',
	// 					),
	// 				),
	// 				'withLayout' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'getItemObject',
	// 						'expandJSKeys',
	// 					),
	// 				),
	// 				'withBlock' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'expandJSKeys',
	// 					),
	// 				),
	// 				'withModule' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'expandJSKeys',
	// 					),
	// 				),
	// 				'withSublevel' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'expandJSKeys',
	// 					),
	// 				),
	// 				'withItemObject' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'getItemObject',
	// 					),
	// 				),
	// 			);

	// 		case POP_RESOURCELOADER_HISTORY:

	// 			return array(
	// 				'documentInitialized' => array(
	// 					POP_RESOURCELOADER_INTERCEPTORS => array(
	// 						'getInterceptors',
	// 						'intercept',
	// 					),
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'fetch',
	// 					),
	// 				),
	// 				'getApplicationURL' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'addPushURLAtts' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'getTopLevelFeedback',
	// 					),
	// 				),
	// 				'removePushURLAtts' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'getTopLevelFeedback',
	// 					),
	// 				),
	// 				'pushState' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 			);

	// 		case POP_RESOURCELOADER_INTERCEPTORS:

	// 			return array(
	// 				'getTarget' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'getFrameTarget',
	// 					),
	// 				),
	// 				'intercept' => array(
	// 					POP_RESOURCELOADER_HISTORY => array(
	// 						'pushState',
	// 					),
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'updateTitle',
	// 					),
	// 				),
	// 			);

	// 		case POP_RESOURCELOADER_JSRUNTIMEMANAGER:

	// 			return array(
	// 				'setBlockURL' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'getBlockTopLevelURL',
	// 					),
	// 				),
	// 				'addTemplateId' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'getUniqueId',
	// 					),
	// 				),
	// 				'getBlockSessionIds' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'getBlockTopLevelURL',
	// 					),
	// 				),
	// 				'getTargetSessionIds' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'getSettingsId',
	// 					),
	// 				),
	// 				'deletePageSectionLastSessionIds' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'getSettingsId',
	// 					),
	// 				),
	// 				'deleteBlockLastSessionIds' => array(
	// 					POP_RESOURCELOADER_POPMANAGER => array(
	// 						'getSettingsId',
	// 						'getBlockTopLevelURL',
	// 					),
	// 				),
	// 			);
				
	// 		case POP_RESOURCELOADER_POPMANAGER:

	// 			return array(
	// 				'init' => array(
	// 					POP_RESOURCELOADER_PAGESECTIONMANAGER => array(
	// 						'getTopLevelSettingsId',
	// 					),
	// 					POP_RESOURCELOADER_HISTORY => array(
	// 						'replaceState',
	// 					),
	// 				),
	// 				'expandJSKeys' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'addPageSectionIds' => array(
	// 					POP_RESOURCELOADER_JSRUNTIMEMANAGER => array(
	// 						'addPageSectionId',
	// 					),
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'initDocument' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'initDomain' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'documentInitialized' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'pageSectionFetchSuccess' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'blockFetchSuccess' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'initPageSection' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'pageSectionInitialized' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'pageSectionNewDOMsInitialized' => array(
	// 					POP_RESOURCELOADER_PAGESECTIONMANAGER => array(
	// 						'getOpenMode',
	// 						'open',
	// 					),
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'initPageSectionBranches' => array(
	// 					POP_RESOURCELOADER_JSRUNTIMEMANAGER => array(
	// 						'deletePageSectionLastSessionIds',
	// 					),
	// 				),
	// 				'triggerDestroyTarget' => array(
	// 					POP_RESOURCELOADER_INTERCEPTORS => array(
	// 						'intercept',
	// 					),
	// 				),
	// 				'destroyTarget' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'destroyPageSectionPage' => array(
	// 					POP_RESOURCELOADER_PAGESECTIONMANAGER => array(
	// 						'close',
	// 					),
	// 				),
	// 				'pageSectionRendered' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'runScriptsBefore' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 				'initBlock' => array(
	// 					POP_RESOURCELOADER_JSRUNTIMEMANAGER => array(
	// 						'setBlockURL',
	// 					),
	// 				),
	// 				'initializeBlock' => array(
	// 					POP_RESOURCELOADER_JSLIBRARYMANAGER => array(
	// 						'execute',
	// 					),
	// 				),
	// 			);
	// 	}

	// 	return parent::get_external_method_calls($resource);
	// }
	
	// function get_public_methods($resource) {

	// 	switch ($resource) {

	// 		case POP_RESOURCELOADER_HISTORY:

	// 			return array(
	// 				'documentInitialized',
	// 			);
				
	// 		case POP_RESOURCELOADER_INTERCEPTORS:

	// 			return array(
	// 				'destroyTarget', 
	// 				'pageSectionNewDOMsInitialized',
	// 			);
				
	// 		case POP_RESOURCELOADER_PAGESECTIONMANAGER:

	// 			return array(
	// 				'isHidden', 
	// 				'offcanvasToggle',
	// 			);
	// 	}
	
	// 	return parent::get_public_methods($resource);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_FrontEnd_ResourceLoaderProcessor();
