<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPTheme_Wassup_Events_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_WebPlatformQueryDataModuleProcessorBase:module-resources',
            array($this, 'getModuleCssResources'),
            10,
            6
        );
    }

    public function getModuleCssResources($resources, array $module, array $templateResource, $template, array $props, $processor)
    {

        // Artificial property added to identify the template when adding module-resources
        if ($resourceloader_att = $processor->getProp($module, $props, 'resourceloader')) {
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
