<?php

class PoPTheme_Wassup_Events_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_WebPlatformQueryDataComponentProcessorBase:module-resources',
            $this->getModuleCssResources(...),
            10,
            6
        );
    }

    public function getModuleCssResources($resources, array $component, array $templateResource, $template, array $props, $processor)
    {

        // Artificial property added to identify the template when adding module-resources
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
