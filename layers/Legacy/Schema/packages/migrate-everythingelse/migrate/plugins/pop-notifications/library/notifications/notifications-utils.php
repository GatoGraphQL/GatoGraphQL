<?php
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\Facades\CustomPostUserTypeAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Notifications_Utils
{
    public static function insertLog($args)
    {
        $pluginapi = PoP_Notifications_FunctionsAPIFactory::getInstance();
        $pluginapi->insertLog($args);
    }

    public static function logUserAction($user_id, $action)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        self::insertLog(
            array(
                'action'      => $action,
                'object_type' => 'User',
                'user_id'     => $user_id,
                'object_id'   => $user_id,
                'object_name' => $userTypeAPI->getUserDisplayName($user_id),
            )
        );
    }

    public static function logPostAction($post_id, $action, $user_id = null)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        if (!$user_id) {
            $user_id = \PoP\Root\App::getState('current-user-id');
        }

        self::insertLog(
            array(
                'user_id' => $user_id,
                'action' => $action,
                'object_type' => 'Post',
                'object_subtype' => $customPostTypeAPI->getCustomPostType($post_id),
                'object_id' => $post_id,
                'object_name' => $customPostTypeAPI->getTitle($post_id),
            )
        );
    }

    public static function notifyAllUsers($post_id, $notification)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $customPostUserTypeAPI = CustomPostUserTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getStatus($post_id) == Status::PUBLISHED) {
            // Delete a previous entry (only one entry per post is allowed)
            PoP_Notifications_API::deleteLog(
                array(
                    'action' => AAL_POP_ACTION_POST_NOTIFIEDALLUSERS,
                    'object_type' => 'Post',
                    'object_id' => $post_id,
                )
            );

            // Insert into the Activity Log
            PoP_Notifications_Utils::insertLog(
                array(
                    'action'      => AAL_POP_ACTION_POST_NOTIFIEDALLUSERS,
                    'object_type' => 'Post',
                    'object_subtype' => $customPostTypeAPI->getCustomPostType($post_id),
                    'user_id'     => $customPostUserTypeAPI->getAuthorID($post_id), //POP_NOTIFICATIONS_USERPLACEHOLDER_SYSTEMNOTIFICATIONS,
                    'object_id'   => $post_id,
                    'object_name' => stripslashes($notification),
                )
            );
        }
    }
}
