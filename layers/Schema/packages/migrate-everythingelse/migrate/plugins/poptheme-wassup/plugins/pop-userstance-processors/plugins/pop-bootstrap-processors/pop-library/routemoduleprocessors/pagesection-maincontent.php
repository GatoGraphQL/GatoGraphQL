<?php

use PoP\Routing\RouteNatures;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;

class UserStance_Bootstrap_Module_MainPageSectionRouteModuleProcessor extends PoP_Module_MainPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_STANCES],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_STANCES_PRO],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_STANCES_AGAINST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL],
            POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_STANCES_PRO_GENERAL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_STANCES_AGAINST_GENERAL],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL_GENERAL],
            POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_STANCES_PRO_ARTICLE],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_STANCES_AGAINST_ARTICLE],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL_ARTICLE],
            POP_USERSTANCE_ROUTE_MYSTANCES => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYSTANCES],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        // Author route modules
        $routemodules = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_AuthorSectionTabPanelBlocks::class, UserStance_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORSTANCES],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_AuthorSectionTabPanelBlocks::class, UserStance_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_PRO],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_AuthorSectionTabPanelBlocks::class, UserStance_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_NEUTRAL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_AuthorSectionTabPanelBlocks::class, UserStance_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_AGAINST],
        );
        foreach ($routemodules as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        // Tag route modules
        $routemodules = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_TagSectionTabPanelBlocks::class, UserStance_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGSTANCES],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_TagSectionTabPanelBlocks::class, UserStance_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGSTANCES_PRO],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_TagSectionTabPanelBlocks::class, UserStance_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGSTANCES_NEUTRAL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_TagSectionTabPanelBlocks::class, UserStance_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGSTANCES_AGAINST],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        // Single route modules
        $routemodules = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_SingleSectionTabPanelBlocks::class, UserStance_Module_Processor_SingleSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_SingleSectionTabPanelBlocks::class, UserStance_Module_Processor_SingleSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_SingleSectionTabPanelBlocks::class, UserStance_Module_Processor_SingleSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_SingleSectionTabPanelBlocks::class, UserStance_Module_Processor_SingleSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL],
        );
        foreach ($routemodules as $route => $module) {
            $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = [
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
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->add(
		new UserStance_Bootstrap_Module_MainPageSectionRouteModuleProcessor()
	);
}, 200);
