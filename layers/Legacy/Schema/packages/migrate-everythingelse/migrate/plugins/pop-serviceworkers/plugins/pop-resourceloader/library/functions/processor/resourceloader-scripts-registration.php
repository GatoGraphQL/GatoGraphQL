<?php

class PoP_ServiceWorkers_WebPlatform_ResourceLoader_ScriptsAndStylesRegistration
{
    public function registerScripts()
    {
        global $pop_resourceloaderprocessor_manager, $popwebplatform_resourceloader_scriptsandstyles_registration;

        // Get all the resources
        $resources = $pop_resourceloaderprocessor_manager->getLoadedResources();

        // Filter them
        $resources = $pop_resourceloaderprocessor_manager->filterJs($resources);

        // Add a hook to remove unwanted resources. Eg:
        // POP_RESOURCELOADER_RESOURCELOADERCONFIG_EXTERNALRESOURCES
        // (These only make sense to be added on the External page)
        $resources = \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_WebPlatform_ResourceLoader_ScriptsAndStylesRegistration:registerScripts',
            $resources
        );

        $bundles = array();
        $bundlegroups = array();

        $popwebplatform_resourceloader_scriptsandstyles_registration->registerResources(POP_RESOURCELOADER_RESOURCETYPE_JS, $resources, $bundles, $bundlegroups);
        $popwebplatform_resourceloader_scriptsandstyles_registration->enqueueScripts(false, $resources, $bundles, $bundlegroups);
    }

    public function registerStyles()
    {
        global $pop_resourceloaderprocessor_manager, $popwebplatform_resourceloader_scriptsandstyles_registration;

        // Get all the resources
        $resources = $pop_resourceloaderprocessor_manager->getLoadedResources();

        // Filter them
        $resources = $pop_resourceloaderprocessor_manager->filterCss($resources);

        // Add a hook to remove unwanted resources.
        $resources = \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_WebPlatform_ResourceLoader_ScriptsAndStylesRegistration:registerStyles',
            $resources
        );

        $bundles = array();
        $bundlegroups = array();
        $popwebplatform_resourceloader_scriptsandstyles_registration->registerResources(POP_RESOURCELOADER_RESOURCETYPE_CSS, $resources, $bundles, $bundlegroups);
        $popwebplatform_resourceloader_scriptsandstyles_registration->enqueueStyles(false, $resources, $bundles, $bundlegroups);
    }
}

/**
 * Initialization
 */
global $pop_serviceworkers_webplatform_resourceloader_scriptsandstyles_registration;
$pop_serviceworkers_webplatform_resourceloader_scriptsandstyles_registration = new PoP_ServiceWorkers_WebPlatform_ResourceLoader_ScriptsAndStylesRegistration();
