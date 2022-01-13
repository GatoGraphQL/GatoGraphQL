<?php

class PoPTheme_Wassup_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
            array($this, 'getManagerDependencies')
        );

        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_CoreProcessors_Bootstrap_ResourceLoaderProcessor:dependencies:multiselect',
            array($this, 'getMultiselectDependencies')
        );

        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_WebPlatformQueryDataModuleProcessorBase:module-resources',
            array($this, 'getModuleCssResources'),
            10,
            6
        );
    }

    public function getModuleCssResources($resources, array $module, array $templateResource, $template, array $props, $processor)
    {
        switch ($module[1]) {
            case PoP_Module_Processor_CustomGroups::MODULE_GROUP_HOME_WELCOME:
            case PoP_Module_Processor_CustomGroups::MODULE_GROUP_HOME_COMPACTWELCOME:
            case PoP_Module_Processor_CustomGroups::MODULE_GROUP_AUTHOR_DESCRIPTION:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_GROUPHOMEWELCOME];
                break;

            case PoP_Module_ProcessorTagMultipleComponents::MODULE_LAYOUT_TAG_DETAILS:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_QUICKLINKGROUPS];
                break;

            case PoP_Module_Processor_MainBlocks::MODULE_BLOCK_AUTHOR:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_GROUPAUTHOR];
                break;

            case PoP_Module_Processor_UserForms::MODULE_FORM_MYPREFERENCES:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_FORMMYPREFERENCES];
                break;

            case PoP_Module_Processor_CommentsBlocks::MODULE_BLOCK_COMMENTS_SCROLL:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_BLOCKCOMMENTS];
                break;
        }

        switch ($template) {
            case POP_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION:
            case POP_TEMPLATE_LAYOUT_PREVIEWPOST:
            case POP_TEMPLATE_LAYOUT_PREVIEWUSER:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_QUICKLINKGROUPS];
                break;

            case POP_TEMPLATE_FORMINPUT_DATERANGE:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_DATERANGEPICKER];
                break;

            case POP_TEMPLATE_FETCHMORE:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_FETCHMORE];
                break;

            case POP_TEMPLATE_BLOCK:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_BLOCK];
                break;

            case POP_TEMPLATE_SOCIALMEDIA_ITEM:
            case POP_TEMPLATE_SOCIALMEDIA:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_SOCIALMEDIA];
                break;

            case POP_TEMPLATE_LAYOUT_USERPOSTINTERACTION:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_FRAMEADDCOMMENTS];
                break;

            case POP_TEMPLATE_SPEECHBUBBLE:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_SPEECHBUBBLE];
                break;

            case POP_TEMPLATE_FORMINPUT_FEATUREDIMAGE_INNER:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_FEATUREDIMAGE];
                break;

            case POP_TEMPLATE_WIDGET:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_WIDGET];
                break;

            case POP_TEMPLATE_LAYOUT_MAXHEIGHT:
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_DYNAMICMAXHEIGHT];
                break;
        }

        if ($processor->getProp($module, $props, 'use-skeletonscreen')) {
            $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_SKELETONSCREEN];
        }

        // Artificial property added to identify the template when adding module-resources
        if ($resourceloader_att = $processor->getProp($module, $props, 'resourceloader')) {
            if ($resourceloader_att == 'block-carousel') {
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_BLOCKCAROUSEL];
            } elseif ($resourceloader_att == 'blockgroup-authorsections') {
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_GROUPAUTHORSECTIONS];
            } elseif ($resourceloader_att == 'functionalblock') {
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_FUNCTIONALBLOCK];
            } elseif ($resourceloader_att == 'functionbutton') {
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_FUNCTIONBUTTON];
            } elseif ($resourceloader_att == 'socialmedia') {
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_SOCIALMEDIA];
            } elseif ($resourceloader_att == 'side-sections-menu') {
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_SIDESECTIONSMENU];
            } elseif ($resourceloader_att == 'littleguy') {
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_LITTLEGUY];
            } elseif ($resourceloader_att == 'block-notifications') {
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_BLOCKNOTIFICATIONS];
            } elseif ($resourceloader_att == 'scroll-notifications') {
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_SCROLLNOTIFICATIONS];
            } elseif ($resourceloader_att == 'structure') {
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_STRUCTURE];
            } elseif ($resourceloader_att == 'layout') {
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_LAYOUT];
            } elseif ($resourceloader_att == 'thumb-feed') {
                $resources[] = [PoPThemeWassup_DynamicCSSResourceLoaderProcessor::class, PoPThemeWassup_DynamicCSSResourceLoaderProcessor::RESOURCE_CSS_FEEDTHUMB];
            } elseif ($resourceloader_att == 'homemessage') {
                $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_HOMEMESSAGE];
            }
        }

        // Allow to inject the $module here for several cases
        if ($module == \PoP\Root\App::getHookManager()->applyFilters(
            'PoPTheme_Wassup_ResourceLoaderProcessor_Hooks:css-resources:collapse-hometop',
            null
        )) {
            $resources[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_COLLAPSEHOMETOP];
        }

        return $resources;
    }

    public function getMultiselectDependencies($dependencies)
    {
        $dependencies[] = [PoPTheme_Wassup_Core_Bootstrap_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_Core_Bootstrap_CSSResourceLoaderProcessor::RESOURCE_CSS_MULTISELECT];
        return $dependencies;
    }

    public function getManagerDependencies($dependencies)
    {
        // Generic css
        $dependencies[] = [PoPThemeWassup_DynamicCSSResourceLoaderProcessor::class, PoPThemeWassup_DynamicCSSResourceLoaderProcessor::RESOURCE_CSS_BACKGROUNDIMAGE];
        $dependencies[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_PAGESECTIONGROUP];
        $dependencies[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_THEMEWASSUP];
        $dependencies[] = [PoPTheme_Wassup_VendorCSSResourceLoaderProcessor::class, PoPTheme_Wassup_VendorCSSResourceLoaderProcessor::RESOURCE_EXTERNAL_CSS_FONTAWESOME];
        return $dependencies;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_ResourceLoaderProcessor_Hooks();
