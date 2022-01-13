<?php

class PoP_ServiceWorkers_ResourceLoader_EM_QTransX_Resources
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_ServiceWorkers_WebPlatform_ResourceLoader_ScriptsAndStylesRegistration:registerScripts',
            array($this, 'modifyResources')
        );
    }

    public function modifyResources($resources)
    {

        // If not using a default language and the selected one doesn't exist, do not add the locale file, because in en/ version there is no en.js file, so the filename becomes drp.js, which does not exist, returning a 404
        if (!getEmQtransxFullcalendarLocaleFilename()) {
            $resources = array_values(
                array_diff(
                    $resources,
                    array(
                        [EM_PoPProcessors_QTX_DynamicJSResourceLoaderProcessor::class, EM_PoPProcessors_QTX_DynamicJSResourceLoaderProcessor::RESOURCE_EXTERNAL_FULLCALENDARLOCALE],
                    )
                )
            );
        }

        return $resources;
    }
}

/**
 * Initialization
 */
new PoP_ServiceWorkers_ResourceLoader_EM_QTransX_Resources();
