<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_AddComments_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_ADDCOMMENTS_ROUTE_ADDCOMMENT => [PoP_Module_Processor_CommentsBlocks::class, PoP_Module_Processor_CommentsBlocks::MODULE_BLOCK_ADDCOMMENT],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_AddComments_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
