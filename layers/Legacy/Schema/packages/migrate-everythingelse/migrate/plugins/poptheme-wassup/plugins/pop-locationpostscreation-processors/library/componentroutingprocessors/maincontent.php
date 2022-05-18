<?php

use PoP\Root\Routing\RequestNature;

class PoP_LocationPostsCreation_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST => [GD_Custom_EM_Module_Processor_CreateUpdatePostBlocks::class, GD_Custom_EM_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_LOCATIONPOST_CREATE],
            POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST => [GD_Custom_EM_Module_Processor_CreateUpdatePostBlocks::class, GD_Custom_EM_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_LOCATIONPOST_UPDATE],
        );
        foreach ($routemodules as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        $default_format_mycontent = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);

        $routemodules_mycontent = array(
            POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS => [GD_Custom_EM_Module_Processor_MySectionBlocks::class, GD_Custom_EM_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYLOCATIONPOSTS_TABLE_EDIT],
        );
        foreach ($routemodules_mycontent as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_TABLE,
                ],
            ];
            if ($default_format_mycontent == POP_FORMAT_TABLE) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routemodules_mycontent_simpleviewpreviews = array(
            POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS => [GD_Custom_EM_Module_Processor_MySectionBlocks::class, GD_Custom_EM_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
        );
        foreach ($routemodules_mycontent_simpleviewpreviews as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_mycontent == POP_FORMAT_SIMPLEVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routemodules_mycontent_fullviewpreviews = array(
            POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS => [GD_Custom_EM_Module_Processor_MySectionBlocks::class, GD_Custom_EM_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW],
        );
        foreach ($routemodules_mycontent_fullviewpreviews as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_mycontent == POP_FORMAT_FULLVIEW) {
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
		new PoP_LocationPostsCreation_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
