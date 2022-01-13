<?php

class PopThemeWassup_AAL_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_WebPlatformQueryDataModuleProcessorBase:module-resources',
            array($this, 'getModuleCssResources'),
            10,
            6
        );
    }

    public function getModuleCssResources($resources, array $module, array $templateResource, $template, array $props, $processor)
    {
        switch ($template) {
            case POP_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION:
                $resources[] = [PopThemeWassup_AAL_CSSResourceLoaderProcessor::class, PopThemeWassup_AAL_CSSResourceLoaderProcessor::RESOURCE_CSS_NOTIFICATIONLAYOUT];
                break;
        }

        return $resources;
    }
}

/**
 * Initialization
 */
new PopThemeWassup_AAL_ResourceLoaderProcessor_Hooks();
