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

        $componentVariations = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR],
        );
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
        }

        $componentVariations = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR],
        );
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
        }

        $componentVariations = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR],
        );
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
        }

        $componentVariations = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_LOCATIONPOSTS_SIDEBAR],
        );
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
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
            'component-variation' => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR],
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
