<?php

use PoP\Routing\RouteNatures;

class PoP_SPAResourceLoader_FrontEndEngine_NatureResources extends PoP_ResourceLoader_NatureResources_ProcessorBase
{
    public function addStandardNatureResources(&$resources, $modulefilter, $options)
    {
                
        // The Initial Loaders page is a particular case:
        // 1. blocks/formats for page POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES are not defined in file settingsprocessor.php, so method getPageResources above will not work
        // 2. it must be handled for several targets, as configured through function getLoadersInitialframes()
        foreach (PoP_SPA_ConfigurationUtils::getBackgroundloadRouteConfigurations() as $route => $configuration) {
            $nature = RouteNatures::STANDARD;
            $ids = array(
                $route,
            );
            $merge = false;
            foreach ($configuration['targets'] as $target) {
                $components = array(
                    'target' => $target,
                );
                PoP_ResourceLoaderProcessorUtils::addResourcesFromCurrentVars($modulefilter, $resources, $nature, $ids, $merge, $components, $options);
            }
        }
    }
}
    
/**
 * Initialize
 */
new PoP_SPAResourceLoader_FrontEndEngine_NatureResources();
