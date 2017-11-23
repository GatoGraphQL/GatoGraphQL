<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_JSResourceLoaderProcessor_Manager /*extends PoP_ResourceLoaderProcessor_Manager*/ {

	var $initialized, $jsobjects, $mapping, /*$enqueued, */$processed, /*$enqueued_resources, */$resources_to_map, $first_script, $scripttag_attributes;
	
	function __construct() {

		// parent::__construct();
	
		$this->initialized = false;
		$this->jsobjects = array();
		$this->mapping = array();
		// $this->enqueued = array();
		$this->processed = array();
		// $this->enqueued_resources = array();
		$this->resources_to_map = array();
		$this->scripttag_attributes = array();
		
		add_filter(
            'PoP_Frontend_ResourceLoaderMappingManager:resources',
            array($this, 'add_resources_to_map')
        );

        // Prepare the htmltag attributes before they are printed in the footer, but after all resources have been enqueued
        // That is needed to calculate PoP_ResourceLoaderProcessorUtils::$noncritical_resources, which happens triggered by `$popfrontend_resourceloader_scriptsandstyles_registration->register_scripts();`
        add_action(
            // 'wp_print_footer_scripts',
            'wp_enqueue_scripts',
            array($this, 'prepare_scripttag_attributes'),
            (PHP_INT_MAX-20)
        );

    	// Allow to add attributes 'async' or 'defer' to the script tag
		// Taken from https://stackoverflow.com/questions/18944027/how-do-i-defer-or-async-this-wordpress-javascript-snippet-to-load-lastly-for-fas
		add_filter(
			'PoP_HTMLTags_Utils:scripttag_attributes', 
			array($this, 'get_scripttag_attributes')
		);
	}

	function init() {

		if (!$this->initialized) {

			$this->initialized = true;

			global $pop_frontend_resourceloader_mappingmanager;
			$this->mapping = $pop_frontend_resourceloader_mappingmanager->get_mapping();
		}
	
		return $processor;
	}
	
	function add($processor, $resources_to_process) {

		// parent::add($processor, $resources_to_process);
	
		foreach ($resources_to_process as $resource) {
		
			// Save the references to the resource's javascript objects
			foreach ($processor->get_jsobjects($resource) as $jsobject) {
				
				// $this->objects[$object] = $this->objects[$object] ?? array();
				$this->jsobjects[$jsobject] = $resource;
			}

			// Extract mapping from the .js file: if it has declared its directory
			// if ($processor->get_dir($resource)) {
			if ($processor->extract_mapping($resource)) {
				$this->resources_to_map[] = $resource;
			}

			// Comment Leo 31/10/2017: can't do it now, because the template-resourceloader depends on the dynamic-template-sources,
			// which are not available yet. Then moved to function `prepare_scripttag_attributes`
			// // Add attributes to the html script/style loading this URL?
			// if ($attributes = $processor->get_scripttag_attributes($resource)) {
			// 	$this->scripttag_attributes[PoP_ResourceLoaderProcessorUtils::get_noconflict_resource_name($resource)] = $attributes;
			// }
		}	
	}

	function add_resources_to_map($resources) {

		return array_merge(
			$resources,
			$this->resources_to_map
		);
	}

	function enqueue_resources($resources, $bundle_ids, $bundlegroup_ids/*, $remove_bundled_resources = true*/) {

		global $pop_resourceloaderprocessor_manager;
		// $added_scripts = array();

		// Enqueue the resources/bundles/bundlegroups
		// In order to calculate the bundle(group) ids, we need to substract first those resources which do not can_bundle, since they will not be inside the bundle files
		$enqueuefile_type = PoP_Frontend_ServerUtils::get_enqueuefile_type();
		$loading_bundle = $enqueuefile_type == 'bundlegroup' || $enqueuefile_type == 'bundle';
		$bundlescripts_properties = array();
		if ($loading_bundle) {

			$version = pop_version();

			// We must enqueue up to 3 files for each bundle(group:
			// Normal, Async and Defer
			// These files may or may not exist, so check
			$attributes = array(
				'' => '', 
				'async' => "async='async'", 
				'defer' => "defer='defer'",
			);

			$fallback = false;
			
			// Enqueue the bundleGroups
			if ($enqueuefile_type == 'bundlegroup') {

				// Enqueue either all the resources, or those who can not be bundled
				global $pop_resourceloader_jsbundlegroupfilegenerator;
				foreach ($bundlegroup_ids as $bundleGroupId) {

					$pop_resourceloader_jsbundlegroupfilegenerator->set_filename($bundleGroupId);
					$pop_resourceloader_jsbundlegroupfilegenerator->set_extension('.js');

					foreach ($attributes as $attribute => $scripttag_attributes) {

						// Check if the file exists
						// We employ function `file_exists()` to validate if the normal/async/defer files exist.
						// 1. Normal: we could expect the bundlegroup to exist, and directly print its path without checking. However, if constant `POP_SERVER_SKIPBUNDLEPAGESWITHPARAMS` is true, then not all URLs will have a bundle(group) file, so in those cases it will fail
						// 2. Defer and Async: we are not storing anywhere if those files were created or not, so gotta check for the physical files. 
						// Using function `file_exists` is not ideal for 2 reasons:
						// 1. It creates a dependency to have the files pre-generated. Then, when creating file service-worker.js in /generate/, we must've run /generate-theme/ before then. This is not right
						// (The bundle(group) files are generated under /generate-theme/)
						// 2. Accessing the disk for each file is not performant (no big issue with bundlegroups, but big with bundles, which could be many)
						$pop_resourceloader_jsbundlegroupfilegenerator->set_attribute($attribute);
						if ($pop_resourceloader_jsbundlegroupfilegenerator->file_exists()) {

							// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers script "utils")
							$script = 'pop-bundlegroup-'.$bundleGroupId.($attribute ? '-'.$attribute : '');
							$bundlescripts_properties[] = array(
								'script' => $script,
								'file-url' => $pop_resourceloader_jsbundlegroupfilegenerator->get_fileurl(),
								'scripttag-attributes' => $scripttag_attributes,
							);

							// $added_scripts[] = $script;
						}
						elseif (!$attribute) {

							// If any normal bundle(group) does not exist, fallback on loading resources
							$fallback = true;

							// We can skip iterating
							break;
						}
					}
				}
			}	
			// Enqueue the bundles
			elseif ($enqueuefile_type == 'bundle') {

				// Enqueue either all the resources, or those who can not be bundled
				global $pop_resourceloader_jsbundlefilegenerator;
				foreach ($bundle_ids as $bundleId) {

					$pop_resourceloader_jsbundlefilegenerator->set_filename($bundleId);
					$pop_resourceloader_jsbundlefilegenerator->set_extension('.js');

					foreach ($attributes as $attribute => $scripttag_attributes) {

						// Check if the file exists
						$pop_resourceloader_jsbundlefilegenerator->set_attribute($attribute);
						if ($pop_resourceloader_jsbundlefilegenerator->file_exists()) {

							// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers script "utils")
							$script = 'pop-bundle-'.$bundleId.($attribute ? '-'.$attribute : '');
							$bundlescripts_properties[] = array(
								'script' => $script,
								'file-url' => $pop_resourceloader_jsbundlefilegenerator->get_fileurl(),
								'scripttag-attributes' => $scripttag_attributes,
							);

							// $added_scripts[] = $script;
						}
						elseif (!$attribute) {

							// If any normal bundle(group) does not exist, fallback on loading resources
							$fallback = true;

							// We can skip iterating
							break;
						}
					}
				}
			}

			// Enqueue all the added scripts, if all of the needed bundle(group)s exist
			if (!$fallback) {

				// // Save the name for the first enqueued resource/bundle/bundleGroup, to localize it
				// $this->first_script = $bundlescripts_properties[0]['script'];

				// The bundlegroup file may not exist (eg: when using flag POP_SERVER_SKIPBUNDLEPAGESWITHPARAMS and loading a page with parameters)
				// In that case, $added_scripts will be empty. Then fallback on loading resources, not bundle(group)s
				// $remove_bundled_resources allows the service-worker.js file to also register all individual files, so they are pre-cached
				// if (!empty($added_scripts)/* && $remove_bundled_resources*/) {

				// For bundles and bundlegroups, those requests that can be bundled will be inside the bundle, so remove from the resources
				$canbundle_resources = $pop_resourceloaderprocessor_manager->filter_can_bundle($resources);
				$resources = array_values(array_diff(
					$resources,
					$canbundle_resources
				));
				// }
			}
		}	

		// // The first script will be a resource, if either we are loading resources, or a bundle(group) file does not exist then use the fallback mechanism
		// if (!$loading_bundle || $fallback) {
			
		// 	// Save the name for the first enqueued resource/bundle/bundleGroup, to localize it
		// 	$this->first_script = PoP_ResourceLoaderProcessorUtils::get_noconflict_resource_name($resources[0]);
		// }

		// Save the name for the first enqueued resource/bundle/bundleGroup, to localize it
		if ($resources) {

			$this->first_script = PoP_ResourceLoaderProcessorUtils::get_noconflict_resource_name($resources[0]);
		}
		elseif ($bundlescripts_properties) {

			$this->first_script = $bundlescripts_properties[0]['script'];
		}
		
		// Enqueue either all the resources, or those who can not be bundled
		// Do it first, because these resources are most likely to be depended-by the scripts in the bundle
		// (including all external resources, when doing POP_SERVER_ACCESSEXTERNALCDNRESOURCES = true)
		foreach ($resources as $resource) {

			// Enqueue the resource
			$processor = $this->get_processor($resource);
			
			// Comment Leo 13/11/2017: if a dependency in inside the bundle, then the corresponding handle will never be registered and this resource will not be added to the page
			// Then, check for the dependencies only when loading resources, not bundle(group)s
			$dependencies = array();
			if (!$loading_bundle) {

				// We must filter the dependencies to only CSS files, for if a script has a dependency to a style or viceversa (WP won't load the resource then)
				$dependencies = $processor->get_dependencies($resource);
				$dependencies = $pop_resourceloaderprocessor_manager->filter_js($dependencies);
				$dependencies = array_map(array('PoP_ResourceLoaderProcessorUtils', 'get_noconflict_resource_name'), $dependencies);
			}

			// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers script "utils")
			$script = PoP_ResourceLoaderProcessorUtils::get_noconflict_resource_name($resource);
			wp_register_script($script, $processor->get_file_url($resource), $dependencies, $processor->get_version($resource), true);
			wp_enqueue_script($script);

			// $added_scripts[] = $script;
		}

		// Enqueue all the added scripts, if all of the needed bundle(group)s exist
		foreach ($bundlescripts_properties as $script_properties) {

			$script = $script_properties['script'];
			$file_url = $script_properties['file-url'];
			$scripttag_attributes = $script_properties['scripttag-attributes'];

			wp_register_script($script, $file_url, array(), $version, true);
			wp_enqueue_script($script);

			if ($scripttag_attributes) {
				$this->scripttag_attributes[$script] = $scripttag_attributes;
			}
		}

		// Save the name for the first enqueued resource/bundle/bundleGroup, to localize it
		// $this->first_script = $added_scripts[0];
	}

	function prepare_scripttag_attributes() {

		// Add attributes to the html script/style loading this URL?
		global $pop_resourceloaderprocessor_manager;
		foreach ($pop_resourceloaderprocessor_manager->get_processors() as $resource => $processor) {

			// Only if it is a JS file
			if ($pop_resourceloaderprocessor_manager->is_js($resource)) {

				if ($attributes = $processor->get_scripttag_attributes($resource)) {
					$this->scripttag_attributes[PoP_ResourceLoaderProcessorUtils::get_noconflict_resource_name($resource)] = $attributes;
				}	
			}
		}
	}

	function localize_scripts() {

		// Also localize the scripts. 
		global $PoPFrontend_Initialization;
		$jquery_constants = $PoPFrontend_Initialization->get_jquery_constants();

		// Comment Leo 07/11/2017: since allowing to enqueue bundles/bundgleGroups, it's not true that pop-manager as a resource is always enqueued
		// Then, simply enqueue the $first_script
		// // It can be done for pop-manager.js, which is added always
		// $script = PoP_ResourceLoaderProcessorUtils::get_noconflict_resource_name(POP_RESOURCELOADER_POPMANAGER);
		$script = $this->first_script;
		wp_localize_script($script, 'M', $jquery_constants);
	}

	function async_load_in_order($resource) {

		return $this->get_processor($resource)->async_load_in_order($resource);
	}

	function filter_async($resources) {

		return array_filter($resources, array($this, 'is_async'));
	}

	function is_async($resource) {

		return $this->get_processor($resource)->is_async($resource);
	}

	function filter_defer($resources) {

		return array_filter($resources, array($this, 'is_defer'));
	}

	function is_defer($resource) {

		return $this->get_processor($resource)->is_defer($resource);
	}

	function get_jsobjects($resource) {

		return $this->get_processor($resource)->get_jsobjects($resource);
	}

	function get_initial_jsmethods() {

		$this->init();

		// Starting point: method `init` in JS object popManager
		$queue = $executionHeap = array(
			'popManager::init',
		);

		while ($queue) {
			
			// Get first element in the queue
			$jsObject_method = array_shift($queue);

			// Obtain the composing JS object and the method
			$parts = explode('::', $jsObject_method);
			$jsObject = $parts[0];
			$method = $parts[1];

			// For that object/method, get all of internal and external method calls and add them to the queue
			$process = array();
			if ($this->mapping['internalMethodCalls'][$jsObject] && $this->mapping['internalMethodCalls'][$jsObject][$method]) {
			
				foreach ($this->mapping['internalMethodCalls'][$jsObject][$method] as $calledMethod) {

					$process[] = $jsObject.'::'.$calledMethod;
				}
			}
			if ($this->mapping['externalMethodCalls'][$jsObject] && $this->mapping['externalMethodCalls'][$jsObject][$method]) {
			
				foreach ($this->mapping['externalMethodCalls'][$jsObject][$method] as $calledJSObject => $calledMethod) {

					$process[] = $calledJSObject.'::'.$calledMethod;
				}
			}
			if ($this->mapping['methodExecutions'][$jsObject] && $this->mapping['methodExecutions'][$jsObject][$method]) {
			
				foreach ($this->mapping['methodExecutions'][$jsObject][$method] as $calledMethod) {

					if ($calledJSObjects = $this->mapping['publicMethods'][$calledMethod]) {

						foreach ($calledJSObjects as $calledJSObject) {

							$process[] = $calledJSObject.'::'.$calledMethod;
						}
					}
				}
			}

			// Process all new objects/methods into the heap
			$process = array_unique(array_diff(
				$process,
				$executionHeap
			));
			$executionHeap = array_merge(
				$executionHeap,
				$process
			);
			$queue = array_merge(
				$queue,
				$process
			);
		}

		// Iterate all objects/methods in the heap, and for each of them, get their popJSLibraryManager.execute calls
		$jsmethods = array();
		foreach ($executionHeap as $jsObject_method) {

			// Obtain the composing JS object and the method
			$parts = explode('::', $jsObject_method);
			$jsObject = $parts[0];
			$method = $parts[1];

			if ($called_jsmethods = $this->mapping['methodExecutions'][$jsObject][$method]) {
				
				$jsmethods = array_merge(
					$jsmethods,
					$called_jsmethods
				);
			}
		}

		return array_unique($jsmethods);
	}

	function add_resources_from_jsmethods(&$resources, $methods, $globalscope_resources = array(), $addInitial = true) {

		$this->init();

		$this->processed = array();
		// $this->enqueued_resources = array();

		// Because we start the execution from popManager.init, then start adding that, always
		if ($addInitial) {
			$this->add_resources_from_jsobjects($resources, 'popManager', array('init'));
		}

		// If we have added template resources, which reference a javascript object (under a global scope),
		// then we gotta incorporate these to make sure to bring those referenced resources as dependencies
		// Eg: calling popFullCalendar.addEvents on em-calendar-inner.tmpl
		foreach ($globalscope_resources as $globalscope_resource) {

			$globalscope_processor = $this->get_processor($globalscope_resource);
			foreach ($globalscope_processor->get_globalscope_method_calls($globalscope_resource) as $globalscope_jsobject => $globalscope_jsobject_methods) {

				$this->add_resources_from_jsobjects($resources, $globalscope_jsobject, $globalscope_jsobject_methods);
			}
		}

		// Obtain what resources have a public method same as the ones being executed
		$publicmethods_jsobjects = array();
		foreach ($methods as $method) {

			if ($publicmethod_jsobjects = $this->mapping['publicMethods'][$method]) {

				foreach ($publicmethod_jsobjects as $jsobject) {
					$publicmethods_jsobjects[$jsobject][] = $method;
				}
			}
		}

		// Enqueue the resources and its dependencies
		foreach ($publicmethods_jsobjects as $jsobject => $jsobject_methods) {

			$this->add_resources_from_jsobjects($resources, $jsobject, $jsobject_methods);
		}

		return $resources;
	}

	protected function add_resources_from_jsobjects(&$resources, $jsobject, $jsobject_methods = array()) {

		$resource = $this->jsobjects[$jsobject];
		if (!$resource) {

			// Comment Leo 21/10/2017: originally we threw an error, stating that not finding a $resource for a given $jsObject is a problem
			// However, there is (at least) a situation in which this doesn't work:
			// - We activate all needed plugins on the STAGING environment
			// - We create file pop-build/resourceloader-mapping.json by invoking /system/build/ on the STAGING environment
			// - This file contains the configuration for all the $jsObjects corresponding to all activated plugins on STAGING
			// - We copy resourceloader-mapping.json from STAGING to PROD on the deployment process
			// - We execute /system/activate-plugins/ on PROD
			// - In that same page, it will already read the configuration from resourceloader-mapping.json to load all needed resources, 
			// - However, the ResourceLoader class is itself not loaded (the plugin was just activated), so then the line below would fail
			// Because of this, instead of throwing an Exception, simply skip loading resources for this $jsObject
			// throw new Exception(sprintf('No Resource for $jsobject \'%s\' (%s)', $jsobject, full_url()));
			return;
		}

		// If some methods from this JS Object have already been processed, remove them
		$processed_methods = $this->processed[$jsobject] ?? array();
		if ($processed_methods) {
			$jsobject_methods = array_diff($jsobject_methods, $processed_methods);
		}

		if ($jsobject_methods) {

			// We have been given a list of $methods to obtain their dependencies
			// These methods will do calls to internal functions (in the same JS object),
			// these methods must also be added (ad infinitum)
			if ($internalMethodCalls = $this->mapping['internalMethodCalls'][$jsobject]) {

				$queue = $jsobject_methods;
				while ($queue) {

					// Check if this methods calls other internal methods which are not in the stack yet, then add them
					$jsobject_method = array_shift($queue);
					$additional_methods = $internalMethodCalls[$jsobject_method] ?? array();
					if ($additional_methods = array_diff($additional_methods, /*$jsobject_methods*/$processed_methods)) {
						
						$queue = array_merge(
							$queue,
							$additional_methods
						);
						$jsobject_methods = array_merge(
							$jsobject_methods,
							$additional_methods
						);
						$processed_methods = array_merge(
							$processed_methods,
							$additional_methods
						);
					}
				}
			}

			// $this->processed[$jsobject] = $this->processed[$jsobject] ?? array();
			// $this->processed[$jsobject] = array_unique(array_merge(
			// 	$this->processed[$jsobject],
			// 	$jsobject_methods
			// ));
			$this->processed[$jsobject] = $processed_methods;

			// First enqueue the dependencies, then continue to enqueue the needed resources
			// Do not add the resource itself: this will be done after all its externalMethod dependencies have been added
			$this->add_resource_dependencies($resources, $resource, false);

			// Enqueue the dependencies needed by the methods
			if ($externalMethodCalls = $this->mapping['externalMethodCalls'][$jsobject]) {
				
				foreach ($jsobject_methods as $jsobject_method) {

					if ($external_jsobjects_methods = $externalMethodCalls[$jsobject_method]) {

						foreach ($external_jsobjects_methods as $external_jsobject => $external_jsobject_methods) {
							
							$this->add_resources_from_jsobjects($resources, $external_jsobject, $external_jsobject_methods);
						}
					}
				}
			}
		}
		
		// Enqueue the resource, at the end, after its dependencies have been added
		$this->add_resource($resources, $resource);
	}

	// Allow to add attributes 'async' or 'defer' to the script tag
	function get_scripttag_attributes($scripttag_attributes) {
		
		return array_merge(
			$scripttag_attributes,
			$this->scripttag_attributes
		);
	}

	// --------------------------------------------------
	// FUNCTIONS FROM $pop_resourceloaderprocessor_manager;
	// --------------------------------------------------

	function get_processor($resource) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->get_processor($resource);
	}

	function get_file_url($resource, $add_version = false) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->get_file_url($resource, $add_version);
	}

	function get_type($resource) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->get_type($resource);
	}

	function get_file_path($resource) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->get_file_path($resource);
	}

	function get_asset_path($resource) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->get_asset_path($resource);
	}

	function filter_js($resources) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->filter_js($resources);
	}

	function filter_css($resources) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->filter_css($resources);
	}

	function filter_can_bundle($resources) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->filter_can_bundle($resources);
	}

	function add_resource(&$resources, $resource/*, $resource_key*/) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->add_resource($resources, $resource);
	}

	function add_resource_dependencies(&$resources, $resource, $add_resource/*, $resource_key = ''*/) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->add_resource_dependencies($resources, $resource, $add_resource);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_jsresourceloaderprocessor_manager;
$pop_jsresourceloaderprocessor_manager = new PoP_JSResourceLoaderProcessor_Manager();
