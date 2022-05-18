<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_LocationPosts_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $components = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR],
        );
        foreach ($components as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = ['component' => $component];
        }

        $components = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR],
        );
        foreach ($components as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
        }

        $components = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR],
        );
        foreach ($components as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
        }

        $components = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_SECTION_LOCATIONPOSTS_SIDEBAR],
        );
        foreach ($components as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        return $ret;
    }

    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $ret[CustomPostRequestNature::CUSTOMPOST][] = [
            'component' => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR],
            'conditions' => [
                'routing' => [
                    'queried-object-post-type' => POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST,
                ],
            ],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_LocationPosts_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
