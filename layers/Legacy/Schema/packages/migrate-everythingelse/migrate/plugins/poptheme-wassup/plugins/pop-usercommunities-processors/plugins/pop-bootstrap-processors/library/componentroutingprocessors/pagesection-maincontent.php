<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class Wassup_URE_RoleProcessors_Bootstrap_Module_MainPageSectionComponentRoutingProcessor extends PoP_Module_MainPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_ComponentProcessor_SectionBlocks::class, PoP_UserCommunities_ComponentProcessor_SectionBlocks::MODULE_BLOCK_TABPANEL_COMMUNITIES],
            POP_USERCOMMUNITIES_ROUTE_MYMEMBERS => [PoP_UserCommunities_ComponentProcessor_SectionBlocks::class, PoP_UserCommunities_ComponentProcessor_SectionBlocks::MODULE_BLOCK_TABPANEL_MYMEMBERS],
        );
        foreach ($routemodules as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        // Author route modules
        $routemodules = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_ComponentProcessor_AuthorSectionBlocks::class, PoP_UserCommunities_ComponentProcessor_AuthorSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCOMMUNITYMEMBERS],
        );
        foreach ($routemodules as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new Wassup_URE_RoleProcessors_Bootstrap_Module_MainPageSectionComponentRoutingProcessor()
	);
}, 200);
