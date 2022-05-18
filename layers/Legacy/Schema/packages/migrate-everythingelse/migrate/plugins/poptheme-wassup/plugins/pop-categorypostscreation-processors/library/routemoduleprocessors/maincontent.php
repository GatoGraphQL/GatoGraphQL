<?php

use PoP\Root\Routing\RequestNature;

class PoP_CategoryPostsCreation_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        // Page modules
        $default_format_mycontent = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);
        $routemodules_mycontent = array(
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS00 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS00_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS01 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS01_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS02 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS02_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS03 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS03_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS04 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS04_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS05 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS05_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS06 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS06_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS07 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS07_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS08 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS08_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS09 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS09_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS10 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS10_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS11 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS11_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS12 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS12_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS13 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS13_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS14 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS14_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS15 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS15_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS16 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS16_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS17 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS17_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS18 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS18_TABLE_EDIT],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS19 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS19_TABLE_EDIT],
        );
        foreach ($routemodules_mycontent as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_TABLE,
                ],
            ];
            if ($default_format_mycontent == POP_FORMAT_TABLE) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_mycontent_simpleviewpreviews = array(
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS00 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS00_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS01 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS01_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS02 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS02_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS03 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS03_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS04 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS04_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS05 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS05_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS06 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS06_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS07 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS07_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS08 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS08_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS09 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS09_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS10 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS10_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS11 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS11_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS12 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS12_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS13 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS13_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS14 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS14_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS15 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS15_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS16 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS16_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS17 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS17_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS18 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS18_SCROLL_SIMPLEVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS19 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS19_SCROLL_SIMPLEVIEWPREVIEW],
        );
        foreach ($routemodules_mycontent_simpleviewpreviews as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_mycontent == POP_FORMAT_SIMPLEVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_mycontent_fullviewpreviews = array(
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS00 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS00_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS01 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS01_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS02 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS02_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS03 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS03_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS04 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS04_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS05 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS05_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS06 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS06_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS07 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS07_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS08 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS08_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS09 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS09_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS10 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS10_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS11 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS11_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS12 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS12_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS13 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS13_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS14 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS14_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS15 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS15_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS16 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS16_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS17 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS17_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS18 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS18_SCROLL_FULLVIEWPREVIEW],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS19 => [LPPC_Module_Processor_MySectionBlocks::class, LPPC_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCATEGORYPOSTS19_SCROLL_FULLVIEWPREVIEW],
        );
        foreach ($routemodules_mycontent_fullviewpreviews as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_mycontent == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
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
		new PoP_CategoryPostsCreation_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
