<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ContentCreation_Notifications_NotificationHooks
{
    public function __construct()
    {

        // Hook into the API: Where statements
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Notifications_API:sql:wheres',
            array($this, 'getWhereStatements'),
            10,
            5
        );

        // Hook into the API: Notification Actions
        \PoP\Root\App::getHookManager()->addFilter(
            'AAL_PoP_API:notifications:useractivityplusnetwork:actions',
            array($this, 'getUseractivityplusnetworkActions')
        );

        \PoP\Root\App::getHookManager()->addFilter(
            'AAL_PoP_API:additional_notifications:markasread:posts:actions',
            array($this, 'getMarkasreadPostActions')
        );
    }

    public function getWhereStatements($sql_where_user_ors, $args, $actions, $user_id, $userposts_where)
    {
        global $wpdb;

        // Admin Notifications:
        // Notify the user when:
        // - The admin approved the user's post
        // - The admin sent back to draft the user's post
        // - The admin trashed the user's post
        $admin_notification_postactions = \PoP\Root\App::getHookManager()->applyFilters(
            'AAL_PoP_API:notifications:admin:post:actions',
            array(
                AAL_POP_ACTION_POST_APPROVEDPOST,
                AAL_POP_ACTION_POST_DRAFTEDPOST,
                AAL_POP_ACTION_POST_TRASHEDPOST,
                AAL_POP_ACTION_POST_SPAMMEDPOST,
            )
        );
        if ($actions) {
            $admin_notification_postactions = array_values(array_intersect($admin_notification_postactions, $actions));
        }
        if ($admin_notification_postactions) {
            $sql_where_user_ors[] = sprintf(
                '
					%3$s.object_type = "Post"
				AND
					%3$s.action in (
						%1$s
					)
				AND
					%2$s
				',
                arrayToQuotedString($admin_notification_postactions),
                $userposts_where,
                $wpdb->pop_notifications
            );
        }

        return $sql_where_user_ors;
    }

    public function getUseractivityplusnetworkActions($actions)
    {

        // User-specific Notifications:
        return array_merge(
            $actions,
            array(
                // Notify the user when:
                // - Someone from the network posts
                AAL_POP_ACTION_POST_CREATEDPOST,
                // AAL_POP_ACTION_POST_CREATEDPENDINGPOST,
                // AAL_POP_ACTION_POST_CREATEDDRAFTPOST,
                // AAL_POP_ACTION_POST_UPDATEDPOST,
                // AAL_POP_ACTION_POST_UPDATEDPENDINGPOST,
                // AAL_POP_ACTION_POST_UPDATEDDRAFTPOST,
                // AAL_POP_ACTION_POST_REMOVEDPOST,
            )
        );
    }

    public function getMarkasreadPostActions($actions)
    {
        return array_merge(
            $actions,
            array(
                AAL_POP_ACTION_POST_CREATEDPOST, // This works only for different user_id co-creating the same post
                AAL_POP_ACTION_POST_UPDATEDPOST,
            )
        );
    }
}

/**
 * Initialize
 */
new PoP_ContentCreation_Notifications_NotificationHooks();
