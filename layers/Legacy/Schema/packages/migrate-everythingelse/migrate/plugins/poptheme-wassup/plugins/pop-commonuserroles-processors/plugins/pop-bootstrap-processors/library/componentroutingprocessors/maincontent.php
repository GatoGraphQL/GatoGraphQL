<?php

use PoP\Root\Routing\RequestNature;

class PoP_CommonUserRolesProcessors_Bootstrap_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS => [GD_URE_Module_Processor_SectionTabPanelBlocks::class, GD_URE_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_ORGANIZATIONS],
            POP_COMMONUSERROLES_ROUTE_INDIVIDUALS => [GD_URE_Module_Processor_SectionTabPanelBlocks::class, GD_URE_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_INDIVIDUALS],
        );
        foreach ($modules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $module,
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
		new PoP_CommonUserRolesProcessors_Bootstrap_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
