<?php

use PoP\Root\Routing\RouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class EMPoP_Bootstrap_Module_MainPageSectionRouteModuleProcessor extends PoP_Module_MainPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_EVENTS_ROUTE_EVENTS => [GD_EM_Module_Processor_SectionTabPanelBlocks::class, GD_EM_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_EVENTS],
            POP_EVENTS_ROUTE_PASTEVENTS => [GD_EM_Module_Processor_SectionTabPanelBlocks::class, GD_EM_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_PASTEVENTS],
            POP_EVENTS_ROUTE_EVENTSCALENDAR => [GD_EM_Module_Processor_SectionTabPanelBlocks::class, GD_EM_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_EVENTSCALENDAR],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        $routemodules = array(
            POP_EVENTS_ROUTE_EVENTS => [GD_EM_Module_Processor_AuthorSectionTabPanelBlocks::class, GD_EM_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHOREVENTS],
            POP_EVENTS_ROUTE_PASTEVENTS => [GD_EM_Module_Processor_AuthorSectionTabPanelBlocks::class, GD_EM_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORPASTEVENTS],
            POP_EVENTS_ROUTE_EVENTSCALENDAR => [GD_EM_Module_Processor_AuthorSectionTabPanelBlocks::class, GD_EM_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHOREVENTSCALENDAR],
        );
        foreach ($routemodules as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        $routemodules = array(
            POP_EVENTS_ROUTE_EVENTS => [GD_EM_Module_Processor_TagSectionTabPanelBlocks::class, GD_EM_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGEVENTS],
            POP_EVENTS_ROUTE_PASTEVENTS => [GD_EM_Module_Processor_TagSectionTabPanelBlocks::class, GD_EM_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGPASTEVENTS],
            POP_EVENTS_ROUTE_EVENTSCALENDAR => [GD_EM_Module_Processor_TagSectionTabPanelBlocks::class, GD_EM_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGEVENTSCALENDAR],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
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
		new EMPoP_Bootstrap_Module_MainPageSectionRouteModuleProcessor()
	);
}, 200);
