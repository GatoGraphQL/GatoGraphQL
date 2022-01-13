<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_BootstrapWebPlatform_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
            array($this, 'getManagerDependencies')
        );
    }

    public function getManagerDependencies($dependencies)
    {

        // Always load Bootstrap
        $dependencies[] = [PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_BOOTSTRAP];
        return $dependencies;
    }
}

/**
 * Initialization
 */
new PoP_BootstrapWebPlatform_ResourceLoaderProcessor_Hooks();
