<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_UserStance_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_USERSTANCE_ROUTE_STANCES => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_STANCES_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_STANCES_STANCE_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_STANCES_STANCE_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_STANCES_STANCE_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
        }

        $modules = array(
            POP_USERSTANCE_ROUTE_STANCES => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHOR_STANCES_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHOR_STANCES_STANCE_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHOR_STANCES_STANCE_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHOR_STANCES_STANCE_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = ['module' => $module];
        }

        $modules = array(
            POP_USERSTANCE_ROUTE_MYSTANCES => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_MYSTANCES_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_STANCES_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_STANCES_STANCE_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_STANCES_STANCE_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_STANCES_STANCE_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_STANCES_STANCE_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_STANCES_STANCE_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_STANCES_STANCE_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_STANCES_GENERALSTANCE_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_STANCES_GENERALSTANCE_SIDEBAR],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_STANCES_GENERALSTANCE_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
        }

        return $ret;
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $ret[CustomPostRequestNature::CUSTOMPOST][] = [
            'module' => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_STANCE_SIDEBAR],
            'conditions' => [
                'routing' => [
                    'queried-object-post-type' => POP_USERSTANCE_POSTTYPE_USERSTANCE,
                ],
            ],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_UserStance_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
