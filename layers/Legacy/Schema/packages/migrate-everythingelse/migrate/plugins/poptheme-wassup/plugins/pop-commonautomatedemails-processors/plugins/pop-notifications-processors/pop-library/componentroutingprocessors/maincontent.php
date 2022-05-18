<?php

use PoP\Root\Routing\RequestNature;

class PoP_CommonAutomatedEmails_AAL_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_AUTOMATEDEMAIL_SCREEN_NOTIFICATIONS);

        $routemodules_details = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY => [PoPTheme_Wassup_AAL_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_AAL_AE_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS],
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
        $routemodules_list = array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY => [PoPTheme_Wassup_AAL_AE_Module_Processor_SectionBlocks::class, PoPTheme_Wassup_AAL_AE_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST],
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
		new PoP_CommonAutomatedEmails_AAL_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
