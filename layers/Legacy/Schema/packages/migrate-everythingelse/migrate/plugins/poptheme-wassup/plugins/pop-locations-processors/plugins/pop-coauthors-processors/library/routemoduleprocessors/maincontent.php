<?php

use PoPSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;

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
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_MAP) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['module' => $module];
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
		new Wassup_EM_CAP_Module_MainContentRouteModuleProcessor()
	);
}, 200);
