<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class UserStance_Bootstrap_Module_MainPageSectionComponentRoutingProcessor extends PoP_Module_MainPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_STANCES],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_STANCES_PRO],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL],
            POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_STANCES_PRO_GENERAL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST_GENERAL],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL_GENERAL],
            POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_STANCES_PRO_ARTICLE],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST_ARTICLE],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL_ARTICLE],
            POP_USERSTANCE_ROUTE_MYSTANCES => [UserStance_Module_Processor_SectionTabPanelBlocks::class, UserStance_Module_Processor_SectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_MYSTANCES],
        );
        foreach ($routemodules as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        // Author route modules
        $routemodules = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_AuthorSectionTabPanelBlocks::class, UserStance_Module_Processor_AuthorSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_AuthorSectionTabPanelBlocks::class, UserStance_Module_Processor_AuthorSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_PRO],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_AuthorSectionTabPanelBlocks::class, UserStance_Module_Processor_AuthorSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_NEUTRAL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_AuthorSectionTabPanelBlocks::class, UserStance_Module_Processor_AuthorSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_AGAINST],
        );
        foreach ($routemodules as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        // Tag route modules
        $routemodules = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_TagSectionTabPanelBlocks::class, UserStance_Module_Processor_TagSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_TAGSTANCES],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_TagSectionTabPanelBlocks::class, UserStance_Module_Processor_TagSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_TAGSTANCES_PRO],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_TagSectionTabPanelBlocks::class, UserStance_Module_Processor_TagSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_TAGSTANCES_NEUTRAL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_TagSectionTabPanelBlocks::class, UserStance_Module_Processor_TagSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_TAGSTANCES_AGAINST],
        );
        foreach ($routemodules as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        // Single route modules
        $routemodules = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_SingleSectionTabPanelBlocks::class, UserStance_Module_Processor_SingleSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_SingleSectionTabPanelBlocks::class, UserStance_Module_Processor_SingleSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_SingleSectionTabPanelBlocks::class, UserStance_Module_Processor_SingleSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_SingleSectionTabPanelBlocks::class, UserStance_Module_Processor_SingleSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL],
        );
        foreach ($routemodules as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
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
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new UserStance_Bootstrap_Module_MainPageSectionComponentRoutingProcessor()
	);
}, 200);
