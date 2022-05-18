<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Misc\RequestUtils;

class PoP_ResourceLoader_EngineInitialization_Hooks {

	public function __construct() {

		\PoP\Root\App::addFilter(
			'PoPWebPlatform_Engine:enqueue-scripts:first-script-handle',
			$this->getFirstScriptHandle(...)
		);

		\PoP\Root\App::addAction(
			'\PoP\ComponentModel\Engine:helperCalculations',
			$this->generateHelperCalculations(...),
			10,
			3
		);

		\PoP\Root\App::addFilter(
			'PoPWebPlatform_Initialization:init-scripts',
			$this->initScripts(...),
			20
		);
	}

	function initScripts($scripts) {

		// Send the list of scripts that have already been included in the body as links/inline
		if (PoP_ResourceLoader_ServerUtils::includeResourcesInBody()) {
			
			// Loading this script can be disabled by SPAResourceLoader
			if (PoP_ResourceLoader_Utils::registerHandlebarshelperScript()) {
				
				PoP_ResourceLoader_Utils::addDynamicModuleResourcesDataToJsobjectConfig($scripts, 'ResourceLoaderHandlebarsHelperHooks');
			}
		}

		return $scripts;
	}

	function generateHelperCalculations($helper_calculations_in_array, array $component, $props_in_array) {

		if (RequestUtils::loadingSite() && PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {

			global $pop_resourcemoduledecoratorprocessor_manager;
			$componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
			$props = &$props_in_array[0];
			$helperCalculations = &$helper_calculations_in_array[0];

			$processor = $componentprocessor_manager->getProcessor($component);
			$processorresourcedecorator = $pop_resourcemoduledecoratorprocessor_manager->getProcessorDecorator($processor);
			
			// Do array_merge because it may already contain data from doing 'extra-uris'
			// Then, "templates" and "component-resources" are all the values required by the current URI and all the extra ones
			$helperCalculations['template-resources'] = array_unique(
				array_merge(
					$helperCalculations['template-resources'] ?? array(),
					$processor->getTemplateResourcesMergedmoduletree($component, $props)
				),
				SORT_REGULAR
			);
			$helperCalculations['component-resources'] = array_unique(
				array_merge(
					$helperCalculations['component-resources'] ?? array(),
					$processorresourcedecorator->getResourcesMergedmoduletree($component, $props)
				),
				SORT_REGULAR
			);
		}
	}

	function getFirstScriptHandle($handle) {

		if (PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {

			global $pop_jsresourceloaderprocessor_manager;
			return $pop_jsresourceloaderprocessor_manager->first_script;
		}

		return $handle;
	}
}

/**
 * Initialization
 */
new PoP_ResourceLoader_EngineInitialization_Hooks();
