<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_ContentCreation_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_CONTENTCREATION_ROUTE_FLAG => [PoP_ContentCreation_Module_Processor_Blocks::class, PoP_ContentCreation_Module_Processor_Blocks::COMPONENT_BLOCK_FLAG],
            POP_CONTENTCREATION_ROUTE_ADDCONTENT => [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::COMPONENT_MULTIPLE_MENU_BODY_ADDCONTENT],
        );
        foreach ($routemodules as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        $default_format_mycontent = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);

        $routemodules_mycontent = array(
            POP_CONTENTCREATION_ROUTE_MYCONTENT => [PoP_ContentCreation_Module_Processor_MySectionBlocks::class, PoP_ContentCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYCONTENT_TABLE_EDIT],
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
            POP_CONTENTCREATION_ROUTE_MYCONTENT => [PoP_ContentCreation_Module_Processor_MySectionBlocks::class, PoP_ContentCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW],
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
            POP_CONTENTCREATION_ROUTE_MYCONTENT => [PoP_ContentCreation_Module_Processor_MySectionBlocks::class, PoP_ContentCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW],
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
		new PoPTheme_Wassup_ContentCreation_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
