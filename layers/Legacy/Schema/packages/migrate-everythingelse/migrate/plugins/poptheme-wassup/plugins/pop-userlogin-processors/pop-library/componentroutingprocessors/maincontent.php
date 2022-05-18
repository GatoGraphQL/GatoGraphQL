<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_UserLogin_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $components = array(
            POP_USERLOGIN_ROUTE_LOGIN => [PoP_Module_Processor_LoginGroups::class, PoP_Module_Processor_LoginGroups::MODULE_GROUP_LOGIN],
            POP_USERLOGIN_ROUTE_LOSTPWD => [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::MODULE_BLOCK_LOSTPWD],
            POP_USERLOGIN_ROUTE_LOSTPWDRESET => [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::MODULE_BLOCK_LOSTPWDRESET],
            POP_USERLOGIN_ROUTE_LOGOUT => [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::MODULE_BLOCK_LOGOUT],
            POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA => [PoP_Module_Processor_UserAccountGroups::class, PoP_Module_Processor_UserAccountGroups::MODULE_GROUP_LOGGEDINUSERDATA],
        );
        foreach ($components as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_UserLogin_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
