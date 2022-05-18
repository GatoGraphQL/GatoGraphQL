<?php

use PoP\Root\Routing\RequestNature;
use PoP\Root\Routing\Routes as RoutingRoutes;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $componentVariations = array(
            POP_ROUTE_AUTHORS => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_POST_POSTAUTHORSSIDEBAR],
        );
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component-variation' => $componentVariation];
        }

        $componentVariations = array(
            RoutingRoutes::$MAIN => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_MAINCONTENT_SIDEBAR],
        );
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
        }

        $componentVariations = array(
            RoutingRoutes::$MAIN => [PoP_Blog_Module_Processor_SidebarMultiples::class, PoP_Blog_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR],
            POP_ROUTE_DESCRIPTION => [PoP_Blog_Module_Processor_SidebarMultiples::class, PoP_Blog_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHOR_SIDEBAR],
        );
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
        }

        return $ret;
    }

    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        // Default for Single
        $ret[CustomPostRequestNature::CUSTOMPOST][] = [
            'component-variation' => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_POST_SIDEBAR]
        ];

        // Default for Author
        $ret[UserRequestNature::USER][] = [
            'component-variation' => [PoP_Blog_Module_Processor_SidebarMultiples::class, PoP_Blog_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHOR_SIDEBAR]
        ];

        // Default for Tag
        $ret[TagRequestNature::TAG][] = [
            'component-variation' => [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_TAG_SIDEBAR]
        ];

        // Default for Home
        // Allow GetPoP website to change the sidebar, since it is changing the homeroute
        $home_componentVariation = \PoP\Root\App::applyFilters(
            'PoPTheme_Wassup_PageSectionSettingsProcessor:sideinfo_home:blockgroup',
            [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR]
        );
        $ret[RequestNature::HOME][] = [
            'component-variation' => $home_componentVariation,
        ];

        return $ret;
    }

    /**
     * @return array<array<string, string[]>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        $ret = array();

        // For the body, instead of adding no SideInfo, add the one that causes the SideInfo to close. This way, if we open a route without Sideinfo (eg: Add Post), from one which does (eg: Posts), then make it close, otherwise the Sideinfo from the 1st route will remain open
        $ret[] = [
            'component-variation' => [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::MODULE_CODE_EMPTYSIDEINFO],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoP_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
