<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\UserState\CheckpointSets\UserStateCheckpointSets;
use PoP\ComponentModel\State\ApplicationState;

trait PoP_UserPlatform_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE,
                POP_USERPLATFORM_ROUTE_EDITPROFILE,
                POP_USERPLATFORM_ROUTE_SETTINGS,
                POP_USERPLATFORM_ROUTE_INVITENEWUSERS,
                POP_USERPLATFORM_ROUTE_MYPROFILE,
                POP_USERPLATFORM_ROUTE_MYPREFERENCES,
            )
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            // Allow the Change Password checkpoints to be overriden. Eg: by adding only non-WSL users
            POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE => HooksAPIFacade::getInstance()->applyFilters(
                'Wassup_Module_SettingsProcessor:changepwdprofile:checkpoints',
                UserStateCheckpointSets::LOGGEDIN_STATIC//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_STATIC),
            ),
            POP_USERPLATFORM_ROUTE_EDITPROFILE => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_USERPLATFORM_ROUTE_MYPREFERENCES => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),//$profile_datafromserver,
            POP_USERPLATFORM_ROUTE_MYPROFILE => UserStateCheckpointSets::LOGGEDIN_STATIC,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_STATIC),
        );
    }

    public function getRedirectUrl()
    {
        $ret = array();

        // Only add the configuration if we are on the corresponding page
        $vars = ApplicationState::getVars();
        if ($vars['routing-state']['is-standard'] && $vars['global-userstate']['is-user-logged-in']) {
            $route = $vars['route'];
            if ($route == POP_USERPLATFORM_ROUTE_EDITPROFILE) {
                // Allow PoP Common User Roles to fill in these redirects according to their roles
                if ($redirect_url = HooksAPIFacade::getInstance()->applyFilters(
                    'UserPlatform:redirect_url:edit_profile',
                    null
                )
                ) {
                    $ret[POP_USERPLATFORM_ROUTE_EDITPROFILE] = $redirect_url;
                }
            } elseif ($route == POP_USERPLATFORM_ROUTE_MYPROFILE) {
                $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
                $current_user_id = $vars['global-userstate']['current-user-id'];
                $ret[POP_USERPLATFORM_ROUTE_MYPROFILE] = $cmsusersapi->getUserURL($current_user_id);
            }
        }

        return $ret;
    }
}
