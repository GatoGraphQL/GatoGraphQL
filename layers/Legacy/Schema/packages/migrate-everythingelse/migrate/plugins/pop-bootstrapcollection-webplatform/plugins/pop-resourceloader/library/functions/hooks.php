<?php

class PoP_CoreProcessors_Bootstrap_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_WebPlatformQueryDataModuleProcessorBase:module-resources',
            array($this, 'getModuleCssResources'),
            10,
            5
        );
    }

    public function getModuleCssResources($resources, array $module, array $templateResource, $template, array $props)
    {
        switch ($template) {
            case POP_TEMPLATE_FORMINPUT_DATERANGE:
                $resources[] = [PoP_CoreProcessors_Bootstrap_VendorCSSResourceLoaderProcessor::class, PoP_CoreProcessors_Bootstrap_VendorCSSResourceLoaderProcessor::RESOURCE_EXTERNAL_CSS_DATERANGEPICKER];
                break;
        }

        return $resources;
    }
}

/**
 * Initialization
 */
new PoP_CoreProcessors_Bootstrap_ResourceLoaderProcessor_Hooks();
