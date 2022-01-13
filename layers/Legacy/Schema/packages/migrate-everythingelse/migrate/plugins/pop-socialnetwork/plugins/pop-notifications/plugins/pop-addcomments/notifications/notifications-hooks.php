<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_AddComments_SocialNetwork_Notifications_NotificationHooks
{
    public function __construct()
    {

        // Hook into the API: Where statements
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_AddComments_Notifications_NotificationHooks:select_from_comment_post_id:unions',
            array($this, 'getSelectFromCommentPostIdUnions'),
            10,
            2
        );
    }

    public function getSelectFromCommentPostIdUnions($select_from_comment_post_id_unions, $user_id)
    {
        return array_merge(
            $select_from_comment_post_id_unions,
            array(
                PoP_SocialNetwork_Notifications_Utils::getUseractivitypostsPostIdUnions($user_id),
                PoP_SocialNetwork_Notifications_Utils::getPosthashashtagtheuserissubscribedtoWhere($user_id),
            )
        );
    }
}

/**
 * Initialize
 */
new PoP_AddComments_SocialNetwork_Notifications_NotificationHooks();
