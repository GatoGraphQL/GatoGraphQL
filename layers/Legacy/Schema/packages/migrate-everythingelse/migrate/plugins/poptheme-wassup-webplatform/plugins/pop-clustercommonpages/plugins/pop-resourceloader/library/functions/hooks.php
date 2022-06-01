<?php

class PoPTheme_Wassup_CommonPages_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_WebPlatformQueryDataComponentProcessorBase:component-resources',
            $this->getComponentCSSResources(...),
            10,
            2
        );
    }

    public function getComponentCSSResources(array $resources, array $component): array
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
