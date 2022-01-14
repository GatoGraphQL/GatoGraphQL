<?php

use PoP\Root\Routing\RequestNature;
use PoPSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_CPL_Bootstrap_Module_MainPageSectionRouteModuleProcessor extends PoP_Module_MainPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_SectionTabPanelBlocks::class, PoP_ContentPostLinks_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_LINKS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        $routemodules = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_AuthorSectionTabPanelBlocks::class, PoP_ContentPostLinks_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORLINKS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        $routemodules = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_TagSectionTabPanelBlocks::class, PoP_ContentPostLinks_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGLINKS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
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
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_CPL_Bootstrap_Module_MainPageSectionRouteModuleProcessor()
	);
}, 200);
