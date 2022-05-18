<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_PostsCreation_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routeComponents = array(
            POP_POSTSCREATION_ROUTE_ADDPOST => [PoP_PostsCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_PostsCreation_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_POST_CREATE],
            POP_POSTSCREATION_ROUTE_EDITPOST => [PoP_PostsCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_PostsCreation_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_POST_UPDATE],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        // Page modules
        $default_format_mycontent = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);

        $routeComponents_mycontent = array(
            POP_POSTSCREATION_ROUTE_MYPOSTS => [PoP_PostsCreation_Module_Processor_MySectionBlocks::class, PoP_PostsCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYPOSTS_TABLE_EDIT],
        );
        foreach ($routeComponents_mycontent as $route => $component) {
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
        $routeComponents_mycontent_simpleviewpreviews = array(
            POP_POSTSCREATION_ROUTE_MYPOSTS => [PoP_PostsCreation_Module_Processor_MySectionBlocks::class, PoP_PostsCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
        );
        foreach ($routeComponents_mycontent_simpleviewpreviews as $route => $component) {
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
        $routeComponents_mycontent_fullviewpreviews = array(
            POP_POSTSCREATION_ROUTE_MYPOSTS => [PoP_PostsCreation_Module_Processor_MySectionBlocks::class, PoP_PostsCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYPOSTS_SCROLL_FULLVIEWPREVIEW],
        );
        foreach ($routeComponents_mycontent_fullviewpreviews as $route => $component) {
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
		new PoPTheme_Wassup_PostsCreation_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
