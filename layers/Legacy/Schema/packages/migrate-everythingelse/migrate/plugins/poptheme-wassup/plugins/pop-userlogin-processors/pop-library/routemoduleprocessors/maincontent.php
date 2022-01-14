<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_UserLogin_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_USERLOGIN_ROUTE_LOGIN => [PoP_Module_Processor_LoginGroups::class, PoP_Module_Processor_LoginGroups::MODULE_GROUP_LOGIN],
            POP_USERLOGIN_ROUTE_LOSTPWD => [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::MODULE_BLOCK_LOSTPWD],
            POP_USERLOGIN_ROUTE_LOSTPWDRESET => [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::MODULE_BLOCK_LOSTPWDRESET],
            POP_USERLOGIN_ROUTE_LOGOUT => [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::MODULE_BLOCK_LOGOUT],
            POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA => [PoP_Module_Processor_UserAccountGroups::class, PoP_Module_Processor_UserAccountGroups::MODULE_GROUP_LOGGEDINUSERDATA],
        );
        foreach ($modules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_UserLogin_Module_MainContentRouteModuleProcessor()
	);
}, 200);
