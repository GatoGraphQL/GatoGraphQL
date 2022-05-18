<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_UserCommunities_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $componentVariations = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_Module_Processor_SidebarMultiples::class, PoP_UserCommunities_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHORCOMMUNITYMEMBERS_SIDEBAR],
        );
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'routing' => [
                        'queried-object-is-community' => true,
                    ],
                ],
            ];
        }

        $componentVariations = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_Module_Processor_SidebarMultiples::class, PoP_UserCommunities_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_COMMUNITIES_SIDEBAR],
            POP_USERCOMMUNITIES_ROUTE_MYMEMBERS => [PoP_UserCommunities_Module_Processor_SidebarMultiples::class, PoP_UserCommunities_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_MYMEMBERS_SIDEBAR],
        );
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_UserCommunities_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
