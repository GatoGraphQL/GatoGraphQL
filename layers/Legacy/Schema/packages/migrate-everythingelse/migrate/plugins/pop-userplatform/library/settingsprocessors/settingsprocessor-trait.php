<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\UserState\CheckpointSets\UserStateCheckpointSets;

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
        if (\PoP\Root\App::getState(['routing', 'is-standard']) && \PoP\Root\App::getState('is-user-logged-in')) {
            $route = \PoP\Root\App::getState('route');
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
                $userTypeAPI = UserTypeAPIFacade::getInstance();
                $current_user_id = \PoP\Root\App::getState('current-user-id');
                $ret[POP_USERPLATFORM_ROUTE_MYPROFILE] = $userTypeAPI->getUserURL($current_user_id);
            }
        }

        return $ret;
    }
}
