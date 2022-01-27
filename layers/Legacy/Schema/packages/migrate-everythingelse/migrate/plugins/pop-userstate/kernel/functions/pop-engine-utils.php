<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_UserState_Utils
{
    public static function currentRouteRequiresUserState()
    {

        // We can force loading the userstate by passing param ?action=loaduserstate
        // Then, for this case, we can assume that the current page requires user state
        if (in_array(POP_ACTION_LOADUSERSTATE, \PoP\Root\App::getState('actions'))) {
            return true;
        }

        // Otherwise, obtain the current page id and check specifically the configuration for that page
        $route = \PoP\Root\App::getState('route');
        return self::routeRequiresUserState($route);
    }

    public static function routeRequiresUserState($route)
    {
        $settingsmanager = \PoPCMSSchema\UserState\Settings\SettingsManagerFactory::getInstance();
        return $settingsmanager->requiresUserState($route);

        // $checkpoint_configuration = RequestUtils::getCheckpointConfiguration($page_id);

        // return self::checkpointConfigurationRequiresUserState($checkpoint_configuration);
    }

    // public static function checkpointConfigurationRequiresUserState($checkpoint_configuration) {

    //     // Allow POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID to not be static => it can't be cached, but to not retrieve the logged-in user data, which:
    //     // 1. It doesn't need
    //     // 2. It creates trouble: it adds PoP_Module_Processor_UserAccountGroups::MODULE_GROUP_LOGGEDINUSERDATA to the output, bringing extra stuff that we don't want, making these pages (eg: /generate-theme/) not have
    //     // the minimum set of templates anymore (eg: it adds "layout-followuser-hide-styles":"layout-styles", so we couldn't use this page to calculate $js/css_loadingframe_resources_pack anymore)
    //     if (isset($checkpoint_configuration['requires-user-state'])) {

    //         return $checkpoint_configuration['requires-user-state'];
    //     }
        
    //     return RequestUtils::checkpointValidationRequired($checkpoint_configuration);
    // }
}
