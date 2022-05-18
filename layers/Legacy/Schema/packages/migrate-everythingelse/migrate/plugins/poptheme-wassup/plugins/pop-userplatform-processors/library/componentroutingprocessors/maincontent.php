<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_UserPlatform_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE => [PoP_UserPlatform_Module_Processor_Blocks::class, PoP_UserPlatform_Module_Processor_Blocks::COMPONENT_BLOCK_USER_CHANGEPASSWORD],
            POP_USERPLATFORM_ROUTE_INVITENEWUSERS => [PoP_UserPlatform_Module_Processor_Blocks::class, PoP_UserPlatform_Module_Processor_Blocks::COMPONENT_BLOCK_INVITENEWUSERS],
            POP_USERPLATFORM_ROUTE_SETTINGS => [PoP_Module_Processor_CustomSettingsBlocks::class, PoP_Module_Processor_CustomSettingsBlocks::COMPONENT_BLOCK_SETTINGS],
            POP_USERPLATFORM_ROUTE_MYPREFERENCES => [PoP_UserPlatform_Module_Processor_Blocks::class, PoP_UserPlatform_Module_Processor_Blocks::COMPONENT_BLOCK_MYPREFERENCES],
            POP_USERPLATFORM_ROUTE_EDITPROFILE => [PoP_UserLogin_Module_Processor_HTMLCodes::class, PoP_UserLogin_Module_Processor_HTMLCodes::COMPONENT_HTMLCODE_USERMUSTBELOGGEDIN],
            POP_USERPLATFORM_ROUTE_MYPROFILE => [PoP_UserLogin_Module_Processor_HTMLCodes::class, PoP_UserLogin_Module_Processor_HTMLCodes::COMPONENT_HTMLCODE_USERMUSTBELOGGEDIN],
        );
        foreach ($routemodules as $route => $component) {
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
		new PoPTheme_Wassup_UserPlatform_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
