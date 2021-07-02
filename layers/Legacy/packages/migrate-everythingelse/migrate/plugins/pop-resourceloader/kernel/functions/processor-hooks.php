<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_ResourceLoader_ProcessorHooks {

	function __construct() {

		HooksAPIFacade::getInstance()->addFilter(
			'PoP_WebPlatformQueryDataModuleProcessorBase:module-immutable-settings',
			array($this, 'getImmutableSettings'),
			10,
			4
		);
	}

	function getImmutableSettings($immutable_settings, array $module, array $props, $processor) {

		// Load the resources. Enable only if enabled by config
		if (PoP_ResourceLoader_ServerUtils::includeResourcesInBody()) {

	        global $pop_resourcemoduledecoratorprocessor_manager;
			if ($resources = $pop_resourcemoduledecoratorprocessor_manager->getProcessordecorator($processor)->getResources($module, $props)) {
				
				$immutable_settings[GD_JS_RESOURCES] = $resources;
			}
		}

		return $immutable_settings;
	}
}

/**
 * Initialization
 */
new PoP_ResourceLoader_ProcessorHooks();
