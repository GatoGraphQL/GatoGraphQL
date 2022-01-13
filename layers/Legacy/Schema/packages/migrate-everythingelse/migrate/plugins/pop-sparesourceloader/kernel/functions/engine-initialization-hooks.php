<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;

class PoP_SPAResourceLoader_EngineInitialization_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoPWebPlatform_Initialization:init-scripts',
            array($this, 'initScripts'),
            20
        );
    }

    public function initScripts($scripts)
    {

        // Comment Leo 27/09/2017: Send the list of resources already loaded to the front-end
        $resources = array();
        if (PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {
            global $popwebplatform_resourceloader_scriptsandstyles_registration;
            $resources = $popwebplatform_resourceloader_scriptsandstyles_registration->getResources();

            // We send the already-loaded resources. Can do it, because these are always the same
            // That's not the case with bundle(group)s, see below
            $scripts[] = sprintf(
                'pop.SPAResourceLoader.loaded = %s;',
                json_encode($resources)
            );

            // Send the list of scripts that have already been included in the body as links/inline
            if (PoP_ResourceLoader_ServerUtils::includeResourcesInBody()) {
                $popSPAResourceLoader = PoP_ResourceLoader_ServerSide_LibrariesFactory::getResourceloaderInstance();
                $scripts[] = sprintf(
                    'pop.SPAResourceLoader["loaded-in-body"] = %s;',
                    json_encode($popSPAResourceLoader->loadedInBody)
                );

                PoP_ResourceLoader_Utils::addDynamicModuleResourcesDataToJsobjectConfig($scripts, 'SPAResourceLoader');
            }
            
            // Printing loaded assets is needed only if repeating the resources from "loading-site" in resources.js (for "fetching-json")
            if (PoP_ResourceLoader_ServerUtils::loadframeResources()) {
                // Comment Leo 07/10/2017: it makes no sense to send the bundle(group) ids, because these ids
                // are different to the ones in the generated files
                // Unless they are taken from the pop-generatecache! (Which were saved when running the generate process)
                // Only then can use
                global $pop_sparesourceloader_mappingstoragemanager;
                if (!$pop_sparesourceloader_mappingstoragemanager->hasCachedEntries()) {
                    $cmsService = CMSServiceFacade::getInstance();
                    $bundle_group_ids = $popwebplatform_resourceloader_scriptsandstyles_registration->getBundlegroupIds();
                    $bundle_ids = $popwebplatform_resourceloader_scriptsandstyles_registration->getBundleIds();
                    $scripts[] = sprintf(
                        'pop.SPAResourceLoader["loaded-by-domain"]["%s"] = %s;',
                        $cmsService->getSiteURL(),
                        json_encode(
                            array(
                                'bundles' => $bundle_ids,
                                'bundle-groups' => $bundle_group_ids,
                            )
                        )
                    );
                }
            }
        }

        return $scripts;
    }
}

/**
 * Initialization
 */
new PoP_SPAResourceLoader_EngineInitialization_Hooks();
