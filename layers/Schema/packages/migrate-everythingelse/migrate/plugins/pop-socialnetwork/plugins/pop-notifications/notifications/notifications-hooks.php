<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_SocialNetwork_Notifications_NotificationHooks
{
    public function __construct()
    {

        // Hook into the API: Notification Actions
        HooksAPIFacade::getInstance()->addFilter(
            'AAL_PoP_API:notifications:useractivityplusnetwork:actions',
            array($this, 'getUseractivityplusnetworkActions')
        );

        HooksAPIFacade::getInstance()->addFilter(
            'AAL_PoP_API:additional_notifications:markasread:posts:actions',
            array($this, 'getMarkasreadPostActions')
        );

        HooksAPIFacade::getInstance()->addFilter(
            'AAL_PoP_API:notifications:userplusnetwork-user:actions',
            array($this, 'getUserplusnetworkUserActions')
        );

        HooksAPIFacade::getInstance()->addFilter(
            'AAL_PoP_API:additional_notifications:markasread:users:actions',
            array($this, 'getMarkasreadUserActions')
        );

        HooksAPIFacade::getInstance()->addFilter(
            'AAL_PoP_API:notifications:userplusnetwork-tag:actions',
            array($this, 'getUserplusnetworkTagActions')
        );

        HooksAPIFacade::getInstance()->addFilter(
            'AAL_PoP_API:additional_notifications:markasread:tags:actions',
            array($this, 'getMarkasreadTagActions')
        );

        HooksAPIFacade::getInstance()->addFilter(
            'AAL_PoP_API:notifications:usernetwork:metakeys',
            array($this, 'getUsernetworkMetakeys')
        );

        HooksAPIFacade::getInstance()->addFilter(
            'AAL_PoP_API:notifications:useractivityposts:post_id_unions',
            array($this, 'getUseractivitypostsPostIdUnions'),
            10,
            2
        );
    }

    public function getUseractivitypostsPostIdUnions($useractivityposts_post_id_unions, $user_id)
    {
        return array_merge(
            $useractivityposts_post_id_unions,
            array(
                PoP_SocialNetwork_Notifications_Utils::getUseractivitypostsPostIdUnions($user_id),
                PoP_SocialNetwork_Notifications_Utils::getPosthashashtagtheuserissubscribedtoWhere($user_id),
            )
        );
    }

    public function getUseractivityplusnetworkActions($actions)
    {

        // User-specific Notifications:
        return array_merge(
            $actions,
            array(
                // Notify the user when:
                // - Someone from the network recommends a post
                // - Anyone recommended the user's post
                AAL_POP_ACTION_POST_RECOMMENDEDPOST,
                AAL_POP_ACTION_POST_UNRECOMMENDEDPOST,
                AAL_POP_ACTION_POST_UPVOTEDPOST,
                AAL_POP_ACTION_POST_UNDIDUPVOTEPOST,
                AAL_POP_ACTION_POST_DOWNVOTEDPOST,
                AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST,
            )
        );
    }

    public function getMarkasreadPostActions($actions)
    {
        return array_merge(
            $actions,
            array(
                AAL_POP_ACTION_POST_RECOMMENDEDPOST,
                AAL_POP_ACTION_POST_UNRECOMMENDEDPOST,
                AAL_POP_ACTION_POST_UPVOTEDPOST,
                AAL_POP_ACTION_POST_UNDIDUPVOTEPOST,
                AAL_POP_ACTION_POST_DOWNVOTEDPOST,
                AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST,
            )
        );
    }

    public function getUserplusnetworkUserActions($actions)
    {
        return array_merge(
            $actions,
            array(
                // Notify the user when:
                // - Someone from the network follows a user
                // - Anyone follows the user
                AAL_POP_ACTION_USER_FOLLOWSUSER,
                AAL_POP_ACTION_USER_UNFOLLOWSUSER,
            )
        );
    }

    public function getMarkasreadUserActions($actions)
    {
        return array_merge(
            $actions,
            array(
                AAL_POP_ACTION_USER_FOLLOWSUSER,
                AAL_POP_ACTION_USER_UNFOLLOWSUSER,
            )
        );
    }

    public function getUserplusnetworkTagActions($actions)
    {
        return array_merge(
            $actions,
            array(
                // Notify the user when:
                // - Someone from the network subscribes to a #hashtag
                AAL_POP_ACTION_USER_SUBSCRIBEDTOTAG,
                AAL_POP_ACTION_USER_UNSUBSCRIBEDFROMTAG,
            )
        );
    }

    public function getMarkasreadTagActions($actions)
    {
        return array_merge(
            $actions,
            array(
                AAL_POP_ACTION_USER_SUBSCRIBEDTOTAG,
                AAL_POP_ACTION_USER_UNSUBSCRIBEDFROMTAG,
            )
        );
    }

    public function getUsernetworkMetakeys($metakeys)
    {
        return array_merge(
            $metakeys,
            array(
                GD_METAKEY_PROFILE_FOLLOWSUSERS,
            )
        );
    }
}

/**
 * Initialize
 */
new PoP_SocialNetwork_Notifications_NotificationHooks();
