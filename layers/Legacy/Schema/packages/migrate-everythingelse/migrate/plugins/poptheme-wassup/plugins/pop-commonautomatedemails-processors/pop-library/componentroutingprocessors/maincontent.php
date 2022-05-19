<?php

use PoP\Root\Routing\RequestNature;

class PoP_CommonAutomatedEmails_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_AUTOMATEDEMAIL_SCREEN_SECTION);

        $routeComponents_single = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL => [PoPTheme_Wassup_AE_Module_Processor_ContentBlocks::class, PoPTheme_Wassup_AE_Module_Processor_ContentBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_SINGLEPOST],
        );
        foreach ($routeComponents_single as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        $routeComponents_details = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY => [PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS],
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
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY => [PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW],
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
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY => [PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW],
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
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY => [PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL],
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
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY => [PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST],
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
		new PoP_CommonAutomatedEmails_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
