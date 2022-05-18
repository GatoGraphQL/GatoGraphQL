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

        $routemodules_single = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL => [PoPTheme_Wassup_AE_Module_Processor_ContentBlocks::class, PoPTheme_Wassup_AE_Module_Processor_ContentBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_SINGLEPOST],
        );
        foreach ($routemodules_single as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        $routemodules_details = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY => [PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $component) {
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
        $routemodules_simpleview = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY => [PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $component) {
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
        $routemodules_fullview = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY => [PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $component) {
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
        $routemodules_thumbnail = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY => [PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $component) {
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
        $routemodules_list = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY => [PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_AE_Module_Processor_SectionBlocks::COMPONENT_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $component) {
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
