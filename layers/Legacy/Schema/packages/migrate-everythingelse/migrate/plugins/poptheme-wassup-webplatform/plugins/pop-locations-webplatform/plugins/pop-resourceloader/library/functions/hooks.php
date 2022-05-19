<?php

class PoPTheme_Wassup_EM_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_WebPlatformQueryDataComponentProcessorBase:component-resources',
            $this->getComponentCSSResources(...),
            10,
            6
        );
    }

    public function getComponentCSSResources($resources, array $component, array $templateResource, $template, array $props, $processor)
    {
        switch ($template) {
            case POP_TEMPLATE_MAP_DIV:
                $resources[] = [PoPTheme_Wassup_EM_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_EM_CSSResourceLoaderProcessor::RESOURCE_CSS_MAP];
                break;
        }

        // Artificial property added to identify the template when adding component-resources
        if ($resourceloader_att = $processor->getProp($component, $props, 'resourceloader')) {
            if ($resourceloader_att == 'map' || $resourceloader_att == 'calendarmap') {
                $resources[] = [PoPTheme_Wassup_EM_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_EM_CSSResourceLoaderProcessor::RESOURCE_CSS_MAP];
            }
        }

        return $resources;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_EM_ResourceLoaderProcessor_Hooks();
