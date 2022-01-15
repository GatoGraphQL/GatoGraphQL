<?php

use PoPSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

class PoPWebPlatform_ResourceLoader_ScriptsAndStylesRegistration {

	var $resources, $bundle_ids, $bundlegroup_ids, $maybe_generated_bundlefiles;
	
	public function __construct() {
	
		$this->resources = array(
			POP_RESOURCELOADER_RESOURCETYPE_JS => array(),
			POP_RESOURCELOADER_RESOURCETYPE_CSS => array(),
		);
		$this->bundle_ids = array(
			POP_RESOURCELOADER_RESOURCETYPE_JS => array(),
			POP_RESOURCELOADER_RESOURCETYPE_CSS => array(),
		);
		$this->bundlegroup_ids = array(
			POP_RESOURCELOADER_RESOURCETYPE_JS => array(),
			POP_RESOURCELOADER_RESOURCETYPE_CSS => array(),
		);
	}

	function getResources() {
	
		return arrayFlatten(
			array_values($this->resources),
			true
		);
	}
	function getBundleIds() {

		return arrayFlatten(array_values($this->bundle_ids));
	}
	function getBundlegroupIds() {

		return arrayFlatten(array_values($this->bundlegroup_ids));
	}

	function registerScripts() {

		$this->registerScriptsOrStyles(POP_RESOURCELOADER_RESOURCETYPE_JS);
	}

	function registerStyles() {

		$this->registerScriptsOrStyles(POP_RESOURCELOADER_RESOURCETYPE_CSS);
	}

	protected function registerScriptsOrStyles($type) {

		global $popwebplatform_resourceloader_scriptsandstyles_manager;
		$resources_pack = PoPWebPlatform_ResourceLoader_ScriptsAndStylesUtils::getResourcesPack($type);
		$this->registerResources($type, $resources_pack['resources']['all'], $resources_pack['bundles']['all'], $resources_pack['bundlegroups']['all'], false);

		// Maybe generate the bundlefiles on runtime
		$this->maybeGenerateBundlefiles($type, $resources_pack['bundles']['all'], $resources_pack['bundlegroups']['all']);

		if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {

			// Enqueue the scripts in this order: 1. Vendor, 2. Normal, 3. Dynamic, 4. Template
			$subtypes = array(
				POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR,
				POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL,
				POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC,
				POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE,
			);
			foreach ($subtypes as $subtype) {

				$resources_pack_by_loading_type = PoPWebPlatform_ResourceLoader_ScriptsAndStylesUtils::getJsResourcesPackByLoadingType($subtype);						
				$immediate_bundlegroups = $async_bundlegroups = $defer_bundlegroups = array();
				if ($immediate_bundleGroupId = $resources_pack_by_loading_type['immediate']['bundlegroup']) {
					$immediate_bundlegroups[] = $immediate_bundleGroupId;
				}
				if ($async_bundleGroupId = $resources_pack_by_loading_type['async']['bundlegroup']) {
					$async_bundlegroups[] = $async_bundleGroupId;
				}
				if ($defer_bundleGroupId = $resources_pack_by_loading_type['defer']['bundlegroup']) {
					$defer_bundlegroups[] = $defer_bundleGroupId;
				}
				
				$this->enqueueScripts($subtype, $resources_pack_by_loading_type['immediate']['resources'], $resources_pack_by_loading_type['immediate']['bundles'], $immediate_bundlegroups);
				$this->enqueueScripts($subtype, $resources_pack_by_loading_type['async']['resources'], $resources_pack_by_loading_type['async']['bundles'], $async_bundlegroups, 'async="async"');
				$this->enqueueScripts($subtype, $resources_pack_by_loading_type['defer']['resources'], $resources_pack_by_loading_type['defer']['bundles'], $defer_bundlegroups, 'defer="defer"');
			}
		}
		elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {

			$this->enqueueStyles(POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR, $resources_pack['resources']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR], $resources_pack['bundles']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR], $resources_pack['bundlegroups']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]);
			$this->enqueueStyles(POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL, $resources_pack['resources']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL], $resources_pack['bundles']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL], $resources_pack['bundlegroups']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]);
			$this->enqueueStyles(POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC, $resources_pack['resources']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC], $resources_pack['bundles']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC], $resources_pack['bundlegroups']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]);
		}
	}

	function registerResources($type, $resources, $bundles, $bundlegroups) {

		$this->resources[$type] = $resources;
		$this->bundle_ids[$type] = $bundles;
		$this->bundlegroup_ids[$type] = $bundlegroups;

		// Comment Leo 21/11/2017: Set the resources on $popResourceLoader, so that CSS resources have their URL to print it in the body
		if (defined('POP_SSR_INITIALIZED')) {
			$this->initResourceloader($type, $resources);
		}

		global $pop_resourceloaderprocessor_manager;
		$inline_resources = $pop_resourceloaderprocessor_manager->filterInline($resources);

		// Get all the resources from the current request, from the loaded Handlebars templates and Javascript methods
		if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
					
			// Enqueue the resources
			global $pop_jsresourceloaderprocessor_manager;
			$pop_jsresourceloaderprocessor_manager->printInlineResources($inline_resources);
			// $pop_jsresourceloaderprocessor_manager->enqueue_resources($resources, $bundles, $bundlegroups);
		}
		elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {

			global $pop_cssresourceloaderprocessor_manager;
			$pop_cssresourceloaderprocessor_manager->printInlineResources($inline_resources);
			// $pop_cssresourceloaderprocessor_manager->enqueue_resources($resources, $bundles, $bundlegroups);
		}
	}

	protected function maybeGenerateBundlefiles($type, $bundles, $bundlegroups) {

		// Maybe generate the bundlefiles on runtime
		if (PoP_ResourceLoader_ServerUtils::loadingBundlefile()) {

			$enqueuefile_type = PoP_ResourceLoader_ServerUtils::getEnqueuefileType();
			$bundlefiles = array();
			if ($enqueuefile_type == 'bundle') {

				$bundlefiles = $bundles;
			}
			elseif ($enqueuefile_type == 'bundlegroup') {
				
				$bundlefiles = $bundlegroups;
			}

			PoPWebPlatform_ResourceLoader_ScriptsAndStylesUtils::maybeGenerateBundlefiles($type, $enqueuefile_type, $bundlefiles);
		}
	}

	function enqueueScripts($subtype, $resources, $bundles, $bundlegroups, $attr = '') {

		$acrossThememodes = ($subtype != POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);

		global $pop_jsresourceloaderprocessor_manager;
		$pop_jsresourceloaderprocessor_manager->enqueueScripts($acrossThememodes, $resources, $bundles, $bundlegroups, $attr);
	}

	function enqueueStyles($subtype, $resources, $bundles, $bundlegroups) {

		$acrossThememodes = ($subtype != POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);

		global $pop_cssresourceloaderprocessor_manager;
		$pop_cssresourceloaderprocessor_manager->enqueueStyles($acrossThememodes, $resources, $bundles, $bundlegroups);
	}

	protected function initResourceloader($type, $resources) {

		// Comment Leo 21/11/2017: set the resources into the $popResourceLoader instance
		global $pop_resourceloaderprocessor_manager;
		$cmsService = CMSServiceFacade::getInstance();
		$sources = array();
		$types = array(
            $type => array(),
		);
		foreach ($resources as $resource) {

            $resourceOutputName = ResourceUtils::getResourceOutputName($resource);
			$sources[$resourceOutputName] = $pop_resourceloaderprocessor_manager->getFileUrl($resource, true);
			$types[$type][] = $resourceOutputName;
		}
		// $inBody = $pop_resourceloaderprocessor_manager->filterInBody($resources);

		// Do a merge below, because this function will be invoked twice: once for the JS and once for the CSS, so don't let them override each other
		$domain = $cmsService->getSiteURL();
		$popResourceLoader = PoP_ResourceLoader_ServerSide_LibrariesFactory::getResourceloaderInstance();
		if (!$popResourceLoader->config[$domain]) {

			$popResourceLoader->config[$domain] = array(
				'resources' => array(),
				'sources' => array(),
				'types' => array(
		            POP_RESOURCELOADER_RESOURCETYPE_JS => array(),
		            POP_RESOURCELOADER_RESOURCETYPE_CSS => array(),
		        ),
		        // 'in-body' => array(),
			);
		}
		$popResourceLoader->config[$domain]['resources'] = array_merge(
			$popResourceLoader->config[$domain]['resources'],
			$resources
		);
		$popResourceLoader->config[$domain]['sources'] = array_merge(
			$popResourceLoader->config[$domain]['sources'],
			$sources
		);
		$popResourceLoader->config[$domain]['types'][$type] = array_merge(
			$popResourceLoader->config[$domain]['types'][$type],
			$types[$type]
		);
		// $popResourceLoader->config[$domain]['in-body'] = array_merge(
		// 	$popResourceLoader->config[$domain]['in-body'],
		// 	$inBody
		// );
	}
}

/**
 * Initialization
 */
global $popwebplatform_resourceloader_scriptsandstyles_registration;
$popwebplatform_resourceloader_scriptsandstyles_registration = new PoPWebPlatform_ResourceLoader_ScriptsAndStylesRegistration();

