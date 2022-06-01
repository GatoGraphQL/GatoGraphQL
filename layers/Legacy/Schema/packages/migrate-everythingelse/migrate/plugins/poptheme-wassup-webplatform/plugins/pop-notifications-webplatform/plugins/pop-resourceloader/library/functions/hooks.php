<?php

class PopThemeWassup_AAL_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_WebPlatformQueryDataComponentProcessorBase:component-resources',
            $this->getComponentCSSResources(...),
            10,
            4
        );
    }

    public function getComponentCSSResources(array $resources, array $component, array $templateResource, string $template): array
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
