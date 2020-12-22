<?php

use PoP\Routing\RouteNatures;

class PoPTheme_Wassup_UserPlatform_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE => [PoP_UserPlatform_Module_Processor_Blocks::class, PoP_UserPlatform_Module_Processor_Blocks::MODULE_BLOCK_USER_CHANGEPASSWORD],
            POP_USERPLATFORM_ROUTE_INVITENEWUSERS => [PoP_UserPlatform_Module_Processor_Blocks::class, PoP_UserPlatform_Module_Processor_Blocks::MODULE_BLOCK_INVITENEWUSERS],
            POP_USERPLATFORM_ROUTE_SETTINGS => [PoP_Module_Processor_CustomSettingsBlocks::class, PoP_Module_Processor_CustomSettingsBlocks::MODULE_BLOCK_SETTINGS],
            POP_USERPLATFORM_ROUTE_MYPREFERENCES => [PoP_UserPlatform_Module_Processor_Blocks::class, PoP_UserPlatform_Module_Processor_Blocks::MODULE_BLOCK_MYPREFERENCES],
            POP_USERPLATFORM_ROUTE_EDITPROFILE => [PoP_UserLogin_Module_Processor_HTMLCodes::class, PoP_UserLogin_Module_Processor_HTMLCodes::MODULE_HTMLCODE_USERMUSTBELOGGEDIN],
            POP_USERPLATFORM_ROUTE_MYPROFILE => [PoP_UserLogin_Module_Processor_HTMLCodes::class, PoP_UserLogin_Module_Processor_HTMLCodes::MODULE_HTMLCODE_USERMUSTBELOGGEDIN],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->add(
		new PoPTheme_Wassup_UserPlatform_Module_MainContentRouteModuleProcessor()
	);
}, 200);
