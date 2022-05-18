<?php

use PoP\Root\Routing\RequestNature;

class UserStance_URE_Bootstrap_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS => [UserStance_URE_Module_Processor_SectionTabPanelBlocks::class, UserStance_URE_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_STANCES_BYORGANIZATIONS],
            POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS => [UserStance_URE_Module_Processor_SectionTabPanelBlocks::class, UserStance_URE_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_STANCES_BYINDIVIDUALS],
        );
        foreach ($routemodules as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
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
		new UserStance_URE_Bootstrap_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
