<?php
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;
use PoPCMSSchema\UserState\Checkpoints\DoingPostUserLoggedInAggregateCheckpoint;

trait PoP_UserPlatform_Module_SettingsProcessor_Trait
{
    private ?DoingPostUserLoggedInAggregateCheckpoint $doingPostUserLoggedInAggregateCheckpoint = null;

    final public function setDoingPostUserLoggedInAggregateCheckpoint(DoingPostUserLoggedInAggregateCheckpoint $doingPostUserLoggedInAggregateCheckpoint): void
    {
        $this->doingPostUserLoggedInAggregateCheckpoint = $doingPostUserLoggedInAggregateCheckpoint;
    }
    final protected function getDoingPostUserLoggedInAggregateCheckpoint(): DoingPostUserLoggedInAggregateCheckpoint
    {
        return $this->doingPostUserLoggedInAggregateCheckpoint ??= $this->instanceManager->getInstance(DoingPostUserLoggedInAggregateCheckpoint::class);
    }
    
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

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            // Allow the Change Password checkpoints to be overriden. Eg: by adding only non-WSL users
            POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE => \PoP\Root\App::applyFilters(
                'Wassup_Module_SettingsProcessor:changepwdprofile:checkpoints',
                [$this->getDoingPostUserLoggedInAggregateCheckpoint()]
            ),
            POP_USERPLATFORM_ROUTE_EDITPROFILE => [$this->getUserLoggedInCheckpoint()],
            POP_USERPLATFORM_ROUTE_MYPREFERENCES => [$this->getUserLoggedInCheckpoint()],
            POP_USERPLATFORM_ROUTE_MYPROFILE => [$this->getDoingPostUserLoggedInAggregateCheckpoint()],
        );
    }

    public function getRedirectUrl()
    {
        $ret = array();

        // Only add the configuration if we are on the corresponding page
        if (\PoP\Root\App::getState(['routing', 'is-generic']) && \PoP\Root\App::getState('is-user-logged-in')) {
            $route = \PoP\Root\App::getState('route');
            if ($route == POP_USERPLATFORM_ROUTE_EDITPROFILE) {
                // Allow PoP Common User Roles to fill in these redirects according to their roles
                if ($redirect_url = \PoP\Root\App::applyFilters(
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
