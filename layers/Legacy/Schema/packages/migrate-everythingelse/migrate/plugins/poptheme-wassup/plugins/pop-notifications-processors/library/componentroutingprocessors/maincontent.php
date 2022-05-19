<?php

use PoP\Root\Routing\RequestNature;

class Wassup_AAL_PoPProcessors_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routeComponents = array(
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD => [GD_AAL_Module_Processor_FunctionsDataloads::class, GD_AAL_Module_Processor_FunctionsDataloads::COMPONENT_DATALOAD_MARKALLNOTIFICATIONSASREAD],
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD => [GD_AAL_Module_Processor_FunctionsDataloads::class, GD_AAL_Module_Processor_FunctionsDataloads::COMPONENT_DATALOAD_MARKNOTIFICATIONASREAD],
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD => [GD_AAL_Module_Processor_FunctionsDataloads::class, GD_AAL_Module_Processor_FunctionsDataloads::COMPONENT_DATALOAD_MARKNOTIFICATIONASUNREAD],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        $default_format_notifications = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_NOTIFICATIONS);

        $routeComponents_details = array(
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS => [AAL_PoPProcessors_Module_Processor_NotificationBlocks::class, AAL_PoPProcessors_Module_Processor_NotificationBlocks::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_DETAILS],
        );
        foreach ($routeComponents_details as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_notifications == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS => [AAL_PoPProcessors_Module_Processor_NotificationBlocks::class, AAL_PoPProcessors_Module_Processor_NotificationBlocks::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_notifications == POP_FORMAT_LIST) {
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
		new Wassup_AAL_PoPProcessors_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
