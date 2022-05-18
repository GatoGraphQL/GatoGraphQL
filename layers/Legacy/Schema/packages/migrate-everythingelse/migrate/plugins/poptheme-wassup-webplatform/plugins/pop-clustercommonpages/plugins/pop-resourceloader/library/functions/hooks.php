<?php

class PoPTheme_Wassup_CommonPages_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_WebPlatformQueryDataComponentProcessorBase:component-resources',
            $this->getModuleCssResources(...),
            10,
            6
        );
    }

    public function getModuleCssResources($resources, array $component, array $templateResource, $template, array $props, $processor)
    {
        switch ($component[1]) {
            case GD_ClusterCommonPages_Module_Processor_CustomScrolls::COMPONENT_SCROLL_OURSPONSORS_SMALLDETAILS:
                $resources[] = [PoPTheme_Wassup_CommonPages_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CommonPages_CSSResourceLoaderProcessor::RESOURCE_CSS_SMALLDETAILS];
                break;
        }

        return $resources;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_CommonPages_ResourceLoaderProcessor_Hooks();
