<?php

class PoPTheme_Wassup_Events_ResourceLoaderProcessor_Hooks
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

    public function getComponentCSSResources(array $resources, array $component, array $templateResource, string $template, array $props, $processor): array
    {

        // Artificial property added to identify the template when adding component-resources
        if ($resourceloader_att = $processor->getProp($component, $props, 'resourceloader')) {
            if ($resourceloader_att == 'calendar' || $resourceloader_att == 'calendarmap') {
                $resources[] = [PoPTheme_Wassup_Events_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_Events_CSSResourceLoaderProcessor::RESOURCE_CSS_CALENDAR];
            }
        }

        return $resources;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_Events_ResourceLoaderProcessor_Hooks();
