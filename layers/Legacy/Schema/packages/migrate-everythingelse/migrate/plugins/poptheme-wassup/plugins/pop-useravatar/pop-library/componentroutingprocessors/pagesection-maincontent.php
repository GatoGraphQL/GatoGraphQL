<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_UserAvatar_Module_MainPageSectionComponentRoutingProcessor extends PoP_Module_MainPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_USERAVATAR_ROUTE_EDITAVATAR => [PoP_UserAvatarProcessors_Module_Processor_UserBlocks::class, PoP_UserAvatarProcessors_Module_Processor_UserBlocks::MODULE_BLOCK_USERAVATAR_UPDATE],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_UserAvatar_Module_MainPageSectionComponentRoutingProcessor()
	);
}, 200);
