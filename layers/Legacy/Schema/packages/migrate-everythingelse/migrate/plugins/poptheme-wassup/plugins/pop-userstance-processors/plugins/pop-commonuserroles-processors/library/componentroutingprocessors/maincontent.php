<?php

use PoP\Root\Routing\RequestNature;

class UserStance_URE_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_votes = PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_STANCES);

        $routemodules_carousels = array(
            POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS => [UserStance_URE_Module_Processor_CustomSectionBlocks::class, UserStance_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_BYORGANIZATIONS_CAROUSEL],
            POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS => [UserStance_URE_Module_Processor_CustomSectionBlocks::class, UserStance_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_BYINDIVIDUALS_CAROUSEL],
        );
        foreach ($routemodules_carousels as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_CAROUSEL) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routemodules_fullview = array(
            POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS => [UserStance_URE_Module_Processor_CustomSectionBlocks::class, UserStance_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS => [UserStance_URE_Module_Processor_CustomSectionBlocks::class, UserStance_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routemodules_thumbnail = array(
            POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS => [UserStance_URE_Module_Processor_CustomSectionBlocks::class, UserStance_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS => [UserStance_URE_Module_Processor_CustomSectionBlocks::class, UserStance_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routemodules_list = array(
            POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS => [UserStance_URE_Module_Processor_CustomSectionBlocks::class, UserStance_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS => [UserStance_URE_Module_Processor_CustomSectionBlocks::class, UserStance_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_LIST) {
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
		new UserStance_URE_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
