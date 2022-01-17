<?php
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// By not expending from class AAL_Hook_Base, this code is de-attached from AAL
class URE_AAL_PoP_Hook_Users /* extends AAL_Hook_Base*/
{
    public function __construct()
    {
        \PoP\Root\App::addAction('gd_update_mycommunities:update', array($this, 'joinedCommunities'), 10, 3);
        \PoP\Root\App::addAction('gd_custom_createupdate_profile:additionalsCreate', array($this, 'newUserCommunities'), 10, 2);
        \PoP\Root\App::addAction('GD_EditMembership:update', array($this, 'communityUpdatedUserMembership'), 10, 2);

        // Updated communities
        \PoP\Root\App::addAction('gd_update_mycommunities:update', array($this, 'updatedCommunities'), 10, 1);

        // parent::__construct();
    }

    public function joinedCommunities($user_id, $form_data, $operationlog)
    {
        $this->userJoinedCommunities($user_id, $operationlog['new-communities']);
    }

    public function newUserCommunities($user_id, $form_data)
    {
        $this->userJoinedCommunities($user_id, $form_data['communities']);
    }

    public function updatedCommunities($user_id)
    {
        PoP_Notifications_Utils::logUserAction($user_id, URE_AAL_POP_ACTION_USER_UPDATEDCOMMUNITIES);
    }

    protected function userJoinedCommunities($user_id, $communities)
    {
        if ($communities) {
            foreach ($communities as $community_id) {
                $this->logCommunityAction($user_id, $community_id, URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY);
            }
        }
    }

    public function communityUpdatedUserMembership($user_id, $community_id)
    {

        // Before logging this action, delete all previous notifications about the same action, so that they don't appear repeated multiple times
        // This is very annoying since the notification shows the current settings, and not the historical ones, so the notifications are really a repetition
        // AAL_Main::instance()->api->deleteUserNotifications($community_id, $user_id, array(URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP));
        PoP_Notifications_API::deleteUserNotifications($community_id, $user_id, array(URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP));

        // Then log the action
        $this->logCommunityAction($community_id, $user_id, URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP);
    }

    protected function logCommunityAction($user_id, $object_id, $action)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        PoP_Notifications_Utils::insertLog(
            array(
                'action'      => $action,
                'object_type' => 'User',
                'user_id'     => $user_id,
                'object_id'   => $object_id,
                'object_name' => $userTypeAPI->getUserDisplayName($object_id),
            )
        );
    }
}
