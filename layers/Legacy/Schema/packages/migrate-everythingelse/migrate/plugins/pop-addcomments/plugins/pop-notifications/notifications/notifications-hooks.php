<?php
use PoPCMSSchema\Comments\Facades\CommentTypeAPIFacade;

class PoP_AddComments_Notifications_NotificationHooks
{
    public function __construct()
    {

        // Hook into the API: Where statements
        \PoP\Root\App::addFilter(
            'PoP_Notifications_API:sql:wheres',
            array($this, 'getWhereStatements'),
            10,
            7
        );

        // Hook into the API: Set Status for multiple notifications
        \PoP\Root\App::addFilter(
            'PoP_Notifications_API:multiple-status:objectids-sql',
            array($this, 'getStatusMultipleNotificationsWhereStatements'),
            10,
            2
        );

        \PoP\Root\App::addFilter(
            'AAL_PoP_API:notifications:useractivityposts:object_id_unions',
            array($this, 'getUseractivitypostsObjectIdUnions'),
            10,
            2
        );
    }

    public function getUseractivitypostsObjectIdUnions($useractivityposts_object_id_unions, $user_id)
    {
        global $wpdb;

        $useractivityposts_comment_id_ors = array(
            sprintf(
                '
					%2$s.user_id = %1$s
				',
                $user_id,
                $wpdb->comments
            ),
        );
        $useractivityposts_comment_id_ors = \PoP\Root\App::applyFilters(
            'AAL_PoP_API:notifications:useractivityposts:comment_id_ors',
            $useractivityposts_comment_id_ors,
            $user_id
        );
        return array_merge(
            $useractivityposts_object_id_unions,
            array(
                sprintf(
                    '
						SELECT
							comment_post_ID
						FROM
							%3$s
						INNER JOIN
							%2$s
						ON
							%3$s.comment_post_ID = %2$s.ID
						WHERE
							(
								%3$s.comment_approved = "1"
							AND
								%2$s.post_status = "publish"
							AND
								(
									%4$s
								)
							)
					',
                    $user_id,
                    $wpdb->posts,
                    $wpdb->comments,
                    '('.implode(') OR (', $useractivityposts_comment_id_ors).')'
                ),
            )
        );
    }

    public function getStatusMultipleNotificationsWhereStatements($multiple_status_data, $notification)
    {
        global $wpdb;

        if ($notification->object_type == "Comments") {
            // Comments: since the URL to them is their post, so many comments on the same post can all be marked as read
            // Allow to override all actions
            $comment_actions = \PoP\Root\App::applyFilters(
                'AAL_PoP_API:additional_notifications:markasread:comments:actions',
                array(
                    AAL_POP_ACTION_COMMENT_ADDED,
                )
            );
            if (in_array($notification->action, $comment_actions)) {
                // Only approved comments
                $commentTypeAPI = CommentTypeAPIFacade::getInstance();
                $comment = $commentTypeAPI->getComment($notification->object_id);
                $actions = $comment_actions;
                $objectids_sql = sprintf(
                    '
						%1$s.object_id in (
							SELECT
								comment_ID
							FROM
								`%2$s`
							WHERE
								(
									comment_post_ID = %3$s
								AND
									comment_approved = "1"
								)
						)
					',
                    $wpdb->pop_notifications,
                    $wpdb->comments,
                    $commentTypeAPI->getCommentPostId($comment)
                );

                return array($objectids_sql, $actions);
            }
        }

        return $multiple_status_data;
    }

    public function getWhereStatements($sql_where_user_ors, $args, $actions, $user_id, $userposts_where, $useractivityposts_where, $user_plus_network_where)
    {
        global $wpdb;

        // Comments:
        // from someone else replying to a comment by the user,
        // posting a comment in a post by the user,
        // By hook (PoP Social Network):
        // comment tagging the user,
        // comment in a post where the user is tagged,
        // comment in a post with a #hashtag the user is subscribed to
        $select_from_comment_post_id_unions = array(
            // Notifications about comments added to posts where the user is the author
            sprintf(
                '
					SELECT
						ID
					FROM
						%2$s
					WHERE
						(
							post_author = %1$s
						AND
							post_status = "publish"
						)
				',
                $user_id,
                $wpdb->posts
            ),
        );
        $notifications_for_all_comments = PoP_AddComments_Notifications_Utils::notifyUserForAllComments($user_id);
        if ($notifications_for_all_comments) {
            // Notifications about comments in posts where the user has added a comment
            $select_from_comment_post_id_unions[] = sprintf(
                '
					SELECT
						comment_post_ID
					FROM
						%2$s
					WHERE
						(
							user_id = %1$s
						AND
							comment_approved = "1"
						)
				',
                $user_id,
                $wpdb->comments
            );
        }
        // Allow plugins to add more conditions
        $select_from_comment_post_id_unions = \PoP\Root\App::applyFilters(
            'PoP_AddComments_Notifications_NotificationHooks:select_from_comment_post_id:unions',
            $select_from_comment_post_id_unions,
            $user_id
        );
        $select_from_comment_id_ors = array(
            sprintf(
                '
					comment_post_ID in (
						%1$s
					)
				',
                implode(' UNION ', $select_from_comment_post_id_unions)
            ),
        );
        // Notifications for comments replying a comment from the user:
        // Only add if $notifications_for_all_comments is false, since, otherwise, that condition also covers this case
        if (!$notifications_for_all_comments) {
            $select_from_comment_id_ors[] = sprintf(
                '
					comment_parent in (
						SELECT
							comment_ID
						FROM
							%2$s
						WHERE
							user_id = %1$s
					)
				',
                $user_id,
                $wpdb->comments
            );
        }
        $select_from_comment_id_ors = \PoP\Root\App::applyFilters(
            'PoP_AddComments_Notifications_NotificationHooks:select_from_comment_id:ors',
            $select_from_comment_id_ors,
            $user_id
        );

        $useractivitycomments_object_id_unions = array(
            sprintf(
                '
					SELECT
						comment_ID
					FROM
						%1$s
					WHERE
						(
							%2$s
						AND
							comment_approved = "1"
						)
				',
                $wpdb->comments,
                '('.implode(') OR (', $select_from_comment_id_ors).')'
            )
        );
        $useractivitycomments_object_id_unions = \PoP\Root\App::applyFilters(
            'PoP_AddComments_Notifications_NotificationHooks:object_id:unions',
            $useractivitycomments_object_id_unions,
            $user_id
        );

        // Posts which fulfil any of the following conditions:
        // - authored by the user,
        // - where the user has ever added a comment,
        // - where the user has been tagged (either post or comment),
        // - have a #hastag that the user is subscribed to,
        // but exclude the user him/herself (the user can recommend or post comments in his/her own posts)
        $useractivitycomments_where = sprintf(
            '
				(
					%2$s.user_id != %1$s
				AND
					%2$s.object_id in (
						%3$s
					)
				)
			',
            $user_id,
            $wpdb->pop_notifications,
            implode(' UNION ', $useractivitycomments_object_id_unions)
        );

        // Comments added by the user
        $usercomments_where = sprintf(
            '
				%3$s.object_id in (
					SELECT
						comment_ID
					FROM
						%2$s
					WHERE
						user_id = %1$s
				)
			',
            $user_id,
            $wpdb->comments,
            $wpdb->pop_notifications
        );


        // Comment Notifications:
        // Notify the user when:
        // - Someone from the network comments,
        // - Anyone comments in a post the user has activity in
        $usercommentsplusnetwork_notification_actions = \PoP\Root\App::applyFilters(
            'AAL_PoP_API:notifications:usercommentsplusnetwork:actions',
            array(
                AAL_POP_ACTION_COMMENT_ADDED,
            )
        );
        if ($actions) {
            $usercommentsplusnetwork_notification_actions = array_values(array_intersect($usercommentsplusnetwork_notification_actions, $actions));
        }
        if ($usercommentsplusnetwork_notification_actions) {
            // Make sure the post is published and the comment belongs to a published post or is not spam
            $sql_where_user_ors[] = sprintf(
                '
					%4$s.object_type = "Comments"
				AND
					%4$s.action in (
						%1$s
					)
				AND
					(
						%2$s
					OR
						%3$s
					)
				AND
					%4$s.object_id in (
						SELECT
							%5$s.comment_ID
						FROM
							%5$s
						INNER JOIN
							%6$s
						ON
							%5$s.comment_post_ID = %6$s.ID
						WHERE
							(
								%5$s.comment_approved = "1"
							AND
								%6$s.post_status = "publish"
							)
					)
				',
                arrayToQuotedString($usercommentsplusnetwork_notification_actions),
                $useractivitycomments_where,
                $user_plus_network_where,
                $wpdb->pop_notifications,
                $wpdb->comments,
                $wpdb->posts
            );
        }

        // Admin Notifications:
        // Notify the user when:
        // - The admin marked the user's comment as spam
        $admin_notification_commentactions = \PoP\Root\App::applyFilters(
            'AAL_PoP_API:notifications:admin:comment:actions',
            array(
                AAL_POP_ACTION_COMMENT_SPAMMEDCOMMENT,
            )
        );
        if ($actions) {
            $admin_notification_commentactions = array_values(array_intersect($admin_notification_commentactions, $actions));
        }
        if ($admin_notification_commentactions) {
            $sql_where_user_ors[] = sprintf(
                '
					%3$s.object_type = "Comments"
				AND
					%3$s.action in (
						%1$s
					)
				AND
					%2$s
				',
                arrayToQuotedString($admin_notification_commentactions),
                $usercomments_where,
                $wpdb->pop_notifications
            );
        }

        return $sql_where_user_ors;
    }
}

/**
 * Initialize
 */
new PoP_AddComments_Notifications_NotificationHooks();
