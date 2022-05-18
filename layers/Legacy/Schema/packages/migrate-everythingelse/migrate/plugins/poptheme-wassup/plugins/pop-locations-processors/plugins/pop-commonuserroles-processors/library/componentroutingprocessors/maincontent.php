<?php

use PoP\Root\Routing\RequestNature;

class Wassup_EM_URE_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules_addons = array(
            POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS => [GD_URE_Module_Processor_CustomScrollMapSectionBlocks::class, GD_URE_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_ORGANIZATIONS_SCROLLMAP],
            POP_COMMONUSERROLES_ROUTE_INDIVIDUALS => [GD_URE_Module_Processor_CustomScrollMapSectionBlocks::class, GD_URE_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_INDIVIDUALS_SCROLLMAP],
        );
        foreach ($routemodules_addons as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_users == POP_FORMAT_MAP) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new Wassup_EM_URE_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
