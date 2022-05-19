<?php

use PoP\Root\Routing\RequestNature;

class PoP_CommonAutomatedEmails_EM_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_AUTOMATEDEMAIL_SCREEN_SECTION);

        $routeComponents_details = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY => [PoPTheme_Wassup_EM_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_EM_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS],
        );
        foreach ($routeComponents_details as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_simpleview = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY => [PoPTheme_Wassup_EM_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_EM_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routeComponents_simpleview as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_fullview = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY => [PoPTheme_Wassup_EM_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_EM_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY => [PoPTheme_Wassup_EM_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_EM_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY => [PoPTheme_Wassup_EM_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_EM_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
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
		new PoP_CommonAutomatedEmails_EM_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
