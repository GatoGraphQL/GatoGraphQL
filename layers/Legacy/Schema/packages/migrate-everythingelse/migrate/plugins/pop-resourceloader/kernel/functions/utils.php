<?php
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

class PoP_ResourceLoader_Utils {

	public static function registerHandlebarshelperScript() {

		// Loading this script can be disabled by SPAResourceLoader
		return \PoP\Root\App::applyFilters(
			'PoP_ResourceLoader_Utils:registerHandlebarshelperScript', 
			true
		);
	}

	public static function addDynamicModuleResourcesDataToJsobjectConfig(&$scripts, $jsObject) {

		// Comment Leo 11/12/2017: get the dynamic module resources, and print already their source and type, since
		// this information will be needed if including those resources in the body when initializing a lazy-load block
		if ($dynamic_module_resources_data = PoPWebPlatform_ResourceLoader_ScriptsAndStylesUtils::getDynamicModuleResourcesData()) {
			$cmsService = CMSServiceFacade::getInstance();
			$dynamic_module_resourcesources = $dynamic_module_resources_data['sources'];
			$dynamic_module_resourcetypes = $dynamic_module_resources_data['types'];
			$domain = $cmsService->getSiteURL();

			$scripts[] = sprintf(
				'pop.%s.config["%s"].sources = %s',
				$jsObject,
				$domain,
				json_encode($dynamic_module_resourcesources)
			);
			foreach ($dynamic_module_resourcetypes as $resourcetype => $resourcetype_resources) {
				
				$scripts[] = sprintf(
					'pop.%s.config["%s"].types["%s"] = %s',
					$jsObject,
					$domain,
					$resourcetype,
					json_encode($resourcetype_resources)
				);
			}
		}
	}
}
