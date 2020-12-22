<?php

use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;

class Wassup_EM_CAP_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);

        $routemodules_map = array(
            POP_ROUTE_AUTHORS => [PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionBlocks::class, PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_SINGLEAUTHORS_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $module) {
            $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_MAP) {
                $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = ['module' => $module];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->add(
		new Wassup_EM_CAP_Module_MainContentRouteModuleProcessor()
	);
}, 200);
