<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPTheme_Wassup_EM_ResourceLoaderProcessor_Hooks
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
        switch ($template) {
            case POP_TEMPLATE_MAP_DIV:
                $resources[] = [PoPTheme_Wassup_EM_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_EM_CSSResourceLoaderProcessor::RESOURCE_CSS_MAP];
                break;
        }

        // Artificial property added to identify the template when adding module-resources
        if ($resourceloader_att = $processor->getProp($module, $props, 'resourceloader')) {
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
