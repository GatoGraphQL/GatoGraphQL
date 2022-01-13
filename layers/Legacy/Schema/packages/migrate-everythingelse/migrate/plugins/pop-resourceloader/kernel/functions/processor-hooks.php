<?php
class PoP_ResourceLoader_ProcessorHooks {

	public function __construct() {

		\PoP\Root\App::getHookManager()->addFilter(
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
			if ($resources = $pop_resourcemoduledecoratorprocessor_manager->getProcessorDecorator($processor)->getResources($module, $props)) {
				
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
