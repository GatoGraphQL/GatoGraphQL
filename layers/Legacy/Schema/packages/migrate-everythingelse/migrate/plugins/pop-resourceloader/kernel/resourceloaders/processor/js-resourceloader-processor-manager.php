<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;

class PoP_JSResourceLoaderProcessorManager {

	var $initialized, $jsobjects, $mapping, /*$enqueued, */$processed, /*$enqueued_resources, */$resources_to_map, $first_script, $scripttag_attributes, $inline_resources;

	public function __construct() {

		// parent::__construct();

		$this->initialized = false;
		$this->jsobjects = array();
		$this->mapping = array();
		// $this->enqueued = array();
		$this->processed = array();
		// $this->enqueued_resources = array();
		$this->resources_to_map = array();
		$this->scripttag_attributes = array();

		$this->inline_resources = array();
		\PoP\Root\App::getHookManager()->addAction('popcms:head', array($this, 'printScripts'));

		\PoP\Root\App::getHookManager()->addFilter(
            'PoP_WebPlatform_ResourceLoaderMappingManager:resources',
            array($this, 'addResourcesToMap')
        );

        // Prepare the htmltag attributes before they are printed in the footer, but after all resources have been enqueued
        // That is needed to calculate PoP_ResourceLoaderProcessorUtils::$noncritical_resources, which happens triggered by `$popwebplatform_resourceloader_scriptsandstyles_registration->registerScripts();`
        \PoP\Root\App::getHookManager()->addAction(
            // 'popcms:printFooterScripts',
            'popcms:enqueueScripts',
            array($this, 'prepareScripttagAttributes'),
            (PHP_INT_MAX-20)
        );

    	// Allow to add attributes 'async' or 'defer' to the script tag
		// Taken from https://stackoverflow.com/questions/18944027/how-do-i-defer-or-async-this-wordpress-javascript-snippet-to-load-lastly-for-fas
		\PoP\Root\App::getHookManager()->addFilter(
			'PoP_HTMLTags_Utils:scripttag_attributes',
			array($this, 'getScripttagAttributes')
		);
	}

	function init() {

		if (!$this->initialized) {

			$this->initialized = true;

			global $pop_webplatform_resourceloader_mappingmanager;
			$this->mapping = $pop_webplatform_resourceloader_mappingmanager->getMapping();

			// Comment Leo 28/11/2017: for popManager, when it does popJSLibraryManager.execute(..., ...),
			// do not follow the mapping for a few function calls. That is because there are generic functions,
			// such as `initBlockRuntimeMemory`, that must be called over the added element on the DOM, but that
			// they do not imply dependency, or otherwise it loads these files ALWAYS, independently if they
			// are actually needed or not on that page (eg: map.js is loaded always, since it has function `initBlockRuntimeMemory`)
			// For these cases, we added a 2nd function, called ...Independent, which does not require the dependency in the mapping
			if ($this->mapping['publicMethods']) {

				$noDependencyMethods = array(
					'initBlockRuntimeMemoryIndependent',
					'initPageSectionRuntimeMemoryIndependent',
					'documentInitializedIndependent',
				);
				foreach ($noDependencyMethods as $method) {

					if (isset($this->mapping['publicMethods'][$method])) {

						unset($this->mapping['publicMethods'][$method]);
					}
				}
			}
			// if ($this->mapping['methodExecutions']['Manager']) {

			// 	/*"methodName": list of execute calls within*/
			// 	$removeExecuteMethods = array(
			// 		/*"initBlockRuntimeMemory": */"initBlockRuntimeMemory",
			// 		/*"initPageSectionRuntimeMemory": */"initPageSectionRuntimeMemory",
			// 	);
			// 	foreach ($this->mapping['methodExecutions']['Manager'] as $method => $calledMethods) {

			// 		$this->mapping['methodExecutions']['Manager'][$method] = array_diff(
			// 			$calledMethods,
			// 			$removeExecuteMethods
			// 		);
			// 	}
			// }
		}

		return $processor;
	}

	function add($processor, $resources_to_process) {

		// parent::add($processor, $resources_to_process);

		foreach ($resources_to_process as $resource) {

			// Save the references to the resource's javascript objects
			foreach ($processor->getJsobjects($resource) as $jsobject) {

				// $this->objects[$object] = $this->objects[$object] ?? array();
				$this->jsobjects[$jsobject] = $resource;
			}

			// Extract mapping from the .js file: if it has declared its directory
			// if ($processor->getDir($resource)) {
			if ($processor->extractMapping($resource)) {
				$this->resources_to_map[] = $resource;
			}

			// Comment Leo 31/10/2017: can't do it now, because the template-resourceloader depends on the dynamic-templates,
			// which are not available yet. Then moved to function `prepareScripttagAttributes`
			// // Add attributes to the html script/style loading this URL?
			// if ($attributes = $processor->getScripttagAttributes($resource)) {
			// 	$this->scripttag_attributes[PoP_ResourceLoaderProcessorUtils::getNoconflictResourceName($resource)] = $attributes;
			// }
		}
	}

	function addResourcesToMap($resources) {

		return array_merge(
			$resources,
			$this->resources_to_map
		);
	}

	function printScript(array $resource) {

		global $pop_resourceloaderprocessor_manager;
		$file = $pop_resourceloaderprocessor_manager->getFilePath($resource);
        $file_contents = file_get_contents($file);
		// $resource_id = PoP_ResourceLoaderProcessorUtils::getNoconflictResourceName($resource);
		$resource_id = $pop_resourceloaderprocessor_manager->getHandle($resource);

		return sprintf(
			'<script id="%s" type="text/javascript">%s</script>',
			$resource_id,
			$file_contents
		);
	}

	function printScripts() {

		if ($this->inline_resources) {

			echo implode(PHP_EOL, array_map(array($this, 'printScript'), $this->inline_resources));
		}
	}

	function printInlineResources($resources) {

		$this->inline_resources = $resources;
	}

	protected function getFile($enqueuefile_type, $acrossThememodes) {

		if ($enqueuefile_type == 'bundlegroup') {

			if ($acrossThememodes) {

				global $pop_resourceloader_acrossthememodes_jsbundlegroupfile;
				return $pop_resourceloader_acrossthememodes_jsbundlegroupfile;
			}

			global $pop_resourceloader_singlethememode_jsbundlegroupfile;
			return $pop_resourceloader_singlethememode_jsbundlegroupfile;
		}
		elseif ($enqueuefile_type == 'bundle') {

			if ($acrossThememodes) {

				global $pop_resourceloader_acrossthememodes_jsbundlefile;
				return $pop_resourceloader_acrossthememodes_jsbundlefile;
			}

			global $pop_resourceloader_singlethememode_jsbundlefile;
			return $pop_resourceloader_singlethememode_jsbundlefile;
		}

		return null;
	}

	function enqueueScripts($acrossThememodes, $resources, $bundle_ids, $bundlegroup_ids, $scripttag_attributes = '') {

		global $pop_resourceloaderprocessor_manager;
		// $added_scripts = array();
		$cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();

		// We can only enqueue the resources that do NOT go in the body or are inlined.
		// Those ones will be added when doing $popResourceLoader->includeResources (in the body), or hardcoded (inline, such as utils-inline.js)
		$resources = $pop_resourceloaderprocessor_manager->getEnqueuableResources($resources);

		// Enqueue the resources/bundles/bundlegroups
		// In order to calculate the bundle(group) ids, we need to substract first those resources which do not canBundle, since they will not be inside the bundle files
		$enqueuefile_type = PoP_ResourceLoader_ServerUtils::getEnqueuefileType();
		$loading_bundle = $enqueuefile_type == 'bundlegroup' || $enqueuefile_type == 'bundle';
		$bundlescripts_properties = array();
		if ($loading_bundle) {

			$version = ApplicationInfoFacade::getInstance()->getVersion();
			$file = $this->getFile($enqueuefile_type, $acrossThememodes);

			// Enqueue the bundleGroups
			if ($enqueuefile_type == 'bundlegroup') {

				foreach ($bundlegroup_ids as $bundleGroupId) {

					$file->setFilename($bundleGroupId.'.js');

					// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers script "utils")
					$script = 'pop-bundlegroup-'.$bundleGroupId;
					$bundlescripts_properties[] = array(
						'script' => $script,
						'file-url' => $file->getFileurl(),
						'version' => PoP_ResourceLoaderProcessorUtils::getBundlegroupVersion($bundleGroupId),
						'scripttag-attributes' => $scripttag_attributes,
					);
				}
			}
			// Enqueue the bundles
			elseif ($enqueuefile_type == 'bundle') {

				foreach ($bundle_ids as $bundleId) {

					$file->setFilename($bundleId.'.js');

					// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers script "utils")
					$script = 'pop-bundle-'.$bundleId;
					$bundlescripts_properties[] = array(
						'script' => $script,
						'file-url' => $file->getFileurl(),
						'version' => PoP_ResourceLoaderProcessorUtils::getBundleVersion($bundleId),
						'scripttag-attributes' => $scripttag_attributes,
					);
				}
			}

			// For bundles and bundlegroups, those requests that can be bundled will be inside the bundle, so remove from the resources
			$canbundle_resources = $pop_resourceloaderprocessor_manager->filterCanBundle($resources);
			$resources = array_values(array_diff(
				$resources,
				$canbundle_resources
			));
		}

		// When enqueuing "vendor", "normal", "dynamic" and "template", this function will be called several times
		// So just save the first script the first time
		if (!$this->first_script) {

			// Save the name for the first enqueued resource/bundle/bundleGroup, to localize it
			if ($resources) {

				// $this->first_script = PoP_ResourceLoaderProcessorUtils::getNoconflictResourceName($resources[0]);
				$this->first_script = $pop_resourceloaderprocessor_manager->getHandle($resources[0]);
			}
			elseif ($bundlescripts_properties) {

				$this->first_script = $bundlescripts_properties[0]['script'];
			}
		}

		// Enqueue either all the resources, or those who can not be bundled
		// Do it first, because these resources are most likely to be depended-by the scripts in the bundle
		// (including all external resources, when doing ACCESS_EXTERNAL_CDN_RESOURCES = true)
		foreach ($resources as $resource) {

			// Enqueue the resource
			$processor = $this->getProcessor($resource);

			// Make sure the file_url is not null (eg: POP_RESOURCELOADER_CDNCONFIG_EXTERNAL, without parameter "url" will return null)
			if ($file_url = $processor->getFileUrl($resource)) {

				// Comment Leo 13/11/2017: if a dependency in inside the bundle, then the corresponding handle will never be registered and this resource will not be added to the page
				// Then, check for the dependencies only when loading resources, not bundle(group)s
				$dependencies = array();
				if (!$loading_bundle) {

					// We must filter the dependencies to only JS files, for if a script has a dependency to a style or viceversa (WP won't load the resource then)
					$dependencies = $processor->getDependencies($resource);
					$dependencies = $pop_resourceloaderprocessor_manager->filterJs($dependencies);

					// Filter out the dependencies which are inline or in-body
					$dependencies = $pop_resourceloaderprocessor_manager->getEnqueuableResources($dependencies);

					// Add 'pop-' before the registered name
					$dependencies = array_map(array(PoP_ResourceLoaderProcessorUtils::class, 'getHandle'), $dependencies);
				}
				// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers script "utils")
				// $script = PoP_ResourceLoaderProcessorUtils::getNoconflictResourceName($resource);
				$script = $processor->getHandle($resource);
				$cmswebplatformapi->registerScript($script, $file_url, $dependencies, $processor->getVersion($resource), $processor->inFooter($resource));
				$cmswebplatformapi->enqueueScript($script);

				if ($scripttag_attributes) {
					$this->scripttag_attributes[$script] = $scripttag_attributes;
				}
			}
		}

		// Enqueue all the added scripts, if all of the needed bundle(group)s exist
		foreach ($bundlescripts_properties as $script_properties) {

			$script = $script_properties['script'];
			$file_url = $script_properties['file-url'];
			$bundlefile_version = $script_properties['version'] ?? $version;
			$scripttag_attributes = $script_properties['scripttag-attributes'];

			$cmswebplatformapi->registerScript($script, $file_url, array(), $bundlefile_version, true);
			$cmswebplatformapi->enqueueScript($script);

			if ($scripttag_attributes) {
				$this->scripttag_attributes[$script] = $scripttag_attributes;
			}
		}
	}

	function prepareScripttagAttributes() {

		// Add attributes to the html script/style loading this URL?
		global $pop_resourceloaderprocessor_manager;
		foreach ($pop_resourceloaderprocessor_manager->getLoadedResourceFullNameProcessorInstances() as $resourceFullName => $processor) {

			// Only if it is a JS file
			$resource = ResourceUtils::getResourceFromFullName($resourceFullName);
			if ($pop_resourceloaderprocessor_manager->isJs($resource)) {

				// Get the current model_instance_id
				$model_instance_id = \PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade::getInstance()->getModelInstanceId();
				if ($attributes = $processor->getScripttagAttributes($resource, $model_instance_id)) {
					// $this->scripttag_attributes[PoP_ResourceLoaderProcessorUtils::getNoconflictResourceName($resource)] = $attributes;
					$this->scripttag_attributes[$pop_resourceloaderprocessor_manager->getHandle($resource)] = $attributes;
				}
			}
		}
	}

	function localizeScripts() {

		// Also localize the scripts.
		global $PoPWebPlatform_Initialization;
		$jqueryConstants = $PoPWebPlatform_Initialization->getJqueryConstants();
		$script = $this->first_script;
		$cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
		$cmswebplatformapi->localizeScript($script, 'pop', array('c' => $jqueryConstants));
	}

	function asyncLoadInOrder(array $resource) {

		return $this->getProcessor($resource)->asyncLoadInOrder($resource);
	}

	// function getEnqueuableResources($resources) {

	// 	return $this->getProcessor($resource)->getEnqueuableResources($resource);
	// }

	function filterAsync($resources) {

		return array_filter($resources, array($this, 'isAsync'));
	}

	function isAsync(array $resource) {

		return $this->getProcessor($resource)->isAsync($resource);
	}

	function filterDefer($resources, $model_instance_id = null) {

		if (!$model_instance_id) {
	        $model_instance_id = \PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade::getInstance()->getModelInstanceId();
	    }

		$defer = array();
		foreach ($resources as $resource) {

			if ($this->getProcessor($resource)->isDefer($resource, $model_instance_id)) {
				$defer[] = $resource;
			}
		}

		return $defer;
		// return array_filter($resources, array($this, 'isDefer'));
	}

	// function isDefer(array $resource) {

	// 	return $this->getProcessor($resource)->isDefer($resource);
	// }

	function getJsobjects(array $resource) {

		return $this->getProcessor($resource)->getJsobjects($resource);
	}

	function getInitialJsmethods() {

		$this->init();

		// Starting point: method `init` in JS object popManager
		$queue = $executionHeap = array(
			'Manager::init',
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
			if ($this->mapping['internalMethodCalls'][$jsObject][$method] ?? null) {
				foreach ($this->mapping['internalMethodCalls'][$jsObject][$method] as $calledMethod) {

					$process[] = $jsObject.'::'.$calledMethod;
				}
			}
			if ($this->mapping['externalMethodCalls'][$jsObject][$method] ?? null) {

				foreach ($this->mapping['externalMethodCalls'][$jsObject][$method] as $calledJSObject => $calledMethod) {

					$process[] = $calledJSObject.'::'.$calledMethod;
				}
			}
			if ($this->mapping['methodExecutions'][$jsObject][$method] ?? null) {

				foreach ($this->mapping['methodExecutions'][$jsObject][$method] as $calledMethod) {

					if ($calledJSObjects = $this->mapping['publicMethods'][$calledMethod] ?? null) {

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

			if ($called_jsmethods = $this->mapping['methodExecutions'][$jsObject][$method] ?? null) {

				$jsmethods = array_merge(
					$jsmethods,
					$called_jsmethods
				);
			}
		}

		return array_unique($jsmethods);
	}

	function addResourcesFromJsmethods(&$resources, $methods, $globalscope_resources = array(), $addInitial = true) {

		$this->init();

		$this->processed = array();
		// $this->enqueued_resources = array();

		// Because we start the execution from popManager.init, then start adding that, always
		if ($addInitial) {
			$this->addResourcesFromJsobjects($resources, 'Manager', array('init'));
		}

		// If we have added template resources, which reference a javascript object (under a global scope),
		// then we gotta incorporate these to make sure to bring those referenced resources as dependencies
		// Eg: calling popFullCalendar.addEvents on em-calendar-inner.tmpl
		foreach ($globalscope_resources as $globalscope_resource) {

			$globalscope_processor = $this->getProcessor($globalscope_resource);
			foreach ($globalscope_processor->getGlobalscopeMethodCalls($globalscope_resource) as $globalscope_jsobject => $globalscope_jsobject_methods) {

				$this->addResourcesFromJsobjects($resources, $globalscope_jsobject, $globalscope_jsobject_methods);
			}
		}

		// Obtain what resources have a public method same as the ones being executed
		$publicmethods_jsobjects = array();
		foreach ($methods as $method) {

			if ($publicmethod_jsobjects = $this->mapping['publicMethods'][$method] ?? null) {

				foreach ($publicmethod_jsobjects as $jsobject) {
					$publicmethods_jsobjects[$jsobject][] = $method;
				}
			}
		}

		// Enqueue the resources and its dependencies
		foreach ($publicmethods_jsobjects as $jsobject => $jsobject_methods) {

			$this->addResourcesFromJsobjects($resources, $jsobject, $jsobject_methods);
		}

		return $resources;
	}

	protected function addResourcesFromJsobjects(&$resources, $jsobject, $jsobject_methods = array()) {

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
			// throw new Exception(sprintf('No Resource for $jsobject \'%s\' (%s)', $jsobject, RequestUtils::getRequestedFullURL()));
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
			if ($internalMethodCalls = $this->mapping['internalMethodCalls'][$jsobject] ?? null) {

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

			$this->processed[$jsobject] = $processed_methods;

			// First enqueue the dependencies, then continue to enqueue the needed resources
			// Do not add the resource itself: this will be done after all its externalMethod dependencies have been added
			$this->addResourceDependencies($resources, $resource, false);

			// Enqueue the dependencies needed by the methods
			if ($externalMethodCalls = $this->mapping['externalMethodCalls'][$jsobject] ?? null) {

				foreach ($jsobject_methods as $jsobject_method) {

					if ($external_jsobjects_methods = $externalMethodCalls[$jsobject_method] ?? null) {

						foreach ($external_jsobjects_methods as $external_jsobject => $external_jsobject_methods) {

							$this->addResourcesFromJsobjects($resources, $external_jsobject, $external_jsobject_methods);
						}
					}
				}
			}
		}

		// Enqueue the resource, at the end, after its dependencies have been added
		$this->addResource($resources, $resource);
	}

	// Allow to add attributes 'async' or 'defer' to the script tag
	function getScripttagAttributes($scripttag_attributes) {

		return array_merge(
			$scripttag_attributes,
			$this->scripttag_attributes
		);
	}

	// --------------------------------------------------
	// FUNCTIONS FROM $pop_resourceloaderprocessor_manager;
	// --------------------------------------------------

	function getProcessor(array $resource) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->getProcessor($resource);
	}

	function getFileUrl(array $resource, $add_version = false) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->getFileUrl($resource, $add_version);
	}

	function getType(array $resource) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->getType($resource);
	}

	function getSubtype(array $resource) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->getSubtype($resource);
	}

	function getFilePath(array $resource) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->getFilePath($resource);
	}

	function getAssetPath(array $resource) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->getAssetPath($resource);
	}

	function filterJs($resources) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->filterJs($resources);
	}

	function filterCss($resources) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->filterCss($resources);
	}

	function filterVendor($resources) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->filterVendor($resources);
	}

	function filterDynamic($resources) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->filterDynamic($resources);
	}

	function filterTemplate($resources) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->filterTemplate($resources);
	}

	function filterCanBundle($resources) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->filterCanBundle($resources);
	}

	function addResource(&$resources, $resource/*, $resource_key*/) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->addResource($resources, $resource);
	}

	function addResourceDependencies(&$resources, array $resource, $addResource/*, $resource_key = ''*/) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->addResourceDependencies($resources, $resource, $addResource);
	}
}

/**
 * Initialization
 */
global $pop_jsresourceloaderprocessor_manager;
$pop_jsresourceloaderprocessor_manager = new PoP_JSResourceLoaderProcessorManager();
