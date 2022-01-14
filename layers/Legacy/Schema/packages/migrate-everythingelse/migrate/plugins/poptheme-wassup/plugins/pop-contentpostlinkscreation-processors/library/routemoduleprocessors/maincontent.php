<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_CPLC_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_mycontent = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);

        $routemodules = array(
            POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_CONTENTPOSTLINK_CREATE],
            POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_CONTENTPOSTLINK_UPDATE],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
        }

        $routemodules_mycontent = array(
            POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS => [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYLINKS_TABLE_EDIT],
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
            POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS => [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
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
            POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS => [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW],
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
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_CPLC_Module_MainContentRouteModuleProcessor()
	);
}, 200);
