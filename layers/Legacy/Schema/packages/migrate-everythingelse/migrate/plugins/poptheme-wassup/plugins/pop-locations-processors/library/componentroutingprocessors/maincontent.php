<?php

use PoP\Root\Routing\RequestNature;

class Wassup_EM_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        // Page modules
        $routemodules_allothers = array(
            POP_LOCATIONS_ROUTE_LOCATIONSMAP => [PoP_Module_Processor_LocationsMapBlocks::class, PoP_Module_Processor_LocationsMapBlocks::MODULE_BLOCK_LOCATIONSMAP],
            POP_LOCATIONS_ROUTE_LOCATIONS => [PoP_Locations_Module_Processor_CustomSectionBlocks::class, PoP_Locations_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_LOCATIONS_SCROLL],
        );
        foreach ($routemodules_allothers as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);

        $routemodules_modals = array(
            POP_LOCATIONS_ROUTE_LOCATIONSMAP => [PoP_Module_Processor_LocationsMapBlocks::class, PoP_Module_Processor_LocationsMapBlocks::MODULE_BLOCK_STATICLOCATIONSMAP],
        );
        foreach ($routemodules_modals as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_MODALS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_MODALS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routemodules_typeahead = array(
            POP_LOCATIONS_ROUTE_LOCATIONS => [PoP_Locations_Module_Processor_CustomSectionDataloads::class, PoP_Locations_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONS_TYPEAHEAD],
        );
        foreach ($routemodules_typeahead as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_section == POP_FORMAT_TYPEAHEAD) {
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
		new Wassup_EM_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
