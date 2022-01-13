<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ServiceWorkers_WebPlatform_ResourceLoader_Initialization
{
    public function __construct()
    {

        // Unhook the default function, instead hook the one here, which registers not just the current resources,
        // but all of them! This way, all resources will make it to the precache list and be cached in SW
        global $popwebplatform_resourceloader_initialization;
        HooksAPIFacade::getInstance()->removeAction('popcms:enqueueScripts', array($popwebplatform_resourceloader_initialization, 'registerScripts'));
        HooksAPIFacade::getInstance()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
        HooksAPIFacade::getInstance()->removeAction('popcms:printStyles', array($popwebplatform_resourceloader_initialization, 'registerStyles'));
        HooksAPIFacade::getInstance()->addAction('popcms:printStyles', array($this, 'registerStyles'));

        // When generating the Service Workers, we need to register all of the CSS files to output them in the precache list
        HooksAPIFacade::getInstance()->addFilter('getResourcesIncludeType', array($this, 'getResourcesIncludeType'));

        // Always use the SW creation in 'resource' mode, to avoid $bundle(group)s being enqueued instead of $resources
        HooksAPIFacade::getInstance()->addFilter('getEnqueuefileType', array($this, 'getEnqueuefileType'));
    }

    public function getResourcesIncludeType($type)
    {

        // When generating the Service Workers, we need to register all of the CSS files to output them in the precache list.
        // By using 'header', the styles will be registered through WP standard function, from where we fetch the resources
        return 'header';
    }

    public function getEnqueuefileType($type)
    {

        // Always use the SW creation in 'resource' mode, to avoid $bundle(group)s being enqueued instead of $resources
        return 'resource';
    }

    public function registerScripts()
    {

        // Register the scripts from the Resource Loader on the current request
        // Only if loading the frame, and it is configured to use code splitting
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel() && RequestUtils::loadingSite() && PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {
            global $pop_serviceworkers_webplatform_resourceloader_scriptsandstyles_registration;
            $pop_serviceworkers_webplatform_resourceloader_scriptsandstyles_registration->registerScripts();
        }
    }

    public function registerStyles()
    {

        // Register the scripts from the Resource Loader on the current request
        // Only if loading the frame, and it is configured to use code splitting
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel() && RequestUtils::loadingSite() && PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {
            global $pop_serviceworkers_webplatform_resourceloader_scriptsandstyles_registration;
            $pop_serviceworkers_webplatform_resourceloader_scriptsandstyles_registration->registerStyles();
        }
    }
}

/**
 * Initialization
 */
// It is initialized inside function systemGenerate()
// new PoP_ServiceWorkers_WebPlatform_ResourceLoader_Initialization();
