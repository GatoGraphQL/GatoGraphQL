<?php

class PoP_CoreProcessors_Bootstrap_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_WebPlatformQueryDataComponentProcessorBase:component-resources',
            $this->getComponentCSSResources(...),
            10,
            5
        );
    }

    public function getComponentCSSResources(array $resources, array $component, array $templateResource, string $template, array $props): array
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
