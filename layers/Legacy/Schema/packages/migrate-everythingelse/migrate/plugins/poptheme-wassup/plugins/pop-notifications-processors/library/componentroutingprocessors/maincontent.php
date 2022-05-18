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

        $routemodules = array(
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD => [GD_AAL_Module_Processor_FunctionsDataloads::class, GD_AAL_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD],
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD => [GD_AAL_Module_Processor_FunctionsDataloads::class, GD_AAL_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_MARKNOTIFICATIONASREAD],
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD => [GD_AAL_Module_Processor_FunctionsDataloads::class, GD_AAL_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD],
        );
        foreach ($routemodules as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
        }

        $default_format_notifications = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_NOTIFICATIONS);

        $routemodules_details = array(
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS => [AAL_PoPProcessors_Module_Processor_NotificationBlocks::class, AAL_PoPProcessors_Module_Processor_NotificationBlocks::MODULE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_notifications == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_list = array(
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS => [AAL_PoPProcessors_Module_Processor_NotificationBlocks::class, AAL_PoPProcessors_Module_Processor_NotificationBlocks::MODULE_BLOCK_NOTIFICATIONS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_notifications == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
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
