<?php

class PoP_AddCommentsTinyMCE_SocialNetwork_Notifications_NotificationHooks
{
    public function __construct()
    {

        // Hook into the API: Where statements
        \PoP\Root\App::addFilter(
            'PoP_AddComments_Notifications_NotificationHooks:select_from_comment_post_id:unions',
            $this->getSelectFromCommentPostIdUnions(...),
            10,
            2
        );

        \PoP\Root\App::addFilter(
            'PoP_AddComments_Notifications_NotificationHooks:select_from_comment_id:ors',
            $this->getSelectFromCommentIdOrs(...),
            10,
            2
        );

        \PoP\Root\App::addFilter(
            'AAL_PoP_API:notifications:useractivityposts:comment_id_ors',
            $this->getUseractivitypostsCommentIdOrs(...),
            10,
            2
        );

        \PoP\Root\App::addFilter(
            'PoP_AddComments_Notifications_NotificationHooks:object_id:unions',
            $this->getUseractivitycommentsObjectIdUnions(...),
            10,
            2
        );
    }

    public function getUseractivitycommentsObjectIdUnions($useractivitycomments_object_id_unions, $user_id)
    {
        global $wpdb;
        return array_merge(
            $useractivitycomments_object_id_unions,
            array(
                sprintf(
                    '
						SELECT
							%3$s.comment_id
						FROM
							%3$s
						INNER JOIN
							%2$s
						ON
							%3$s.comment_id = %2$s.comment_ID
						WHERE
							(
								%2$s.comment_approved = "1"
							AND
								%3$s.meta_key = "%4$s"
							AND
								%3$s.meta_value = %1$s
							)
					',
                    $user_id,
                    $wpdb->comments,
                    $wpdb->commentmeta,
                    \PoPCMSSchema\CommentMeta\Utils::getMetaKey(GD_METAKEY_COMMENT_TAGGEDUSERS)
                ),
            )
        );
    }

    public function getSelectFromCommentPostIdUnions($select_from_comment_post_id_unions, $user_id)
    {
        $notifications_for_all_comments = PoP_AddComments_Notifications_Utils::notifyUserForAllComments($user_id);
        if ($notifications_for_all_comments) {
            // Notify user about any comment added to a post, containing one comment where the user has been tagged
            global $wpdb;
            $select_from_comment_post_id_unions[] = sprintf(
                '
					SELECT
						%2$s.comment_post_ID
					FROM
						%2$s
					INNER join
						%3$s
					ON
						%2$s.comment_ID = %3$s.comment_id
					WHERE
							%3$s.meta_key = "%4$s"
						AND
							%3$s.meta_value = %1$s
						AND
							%2$s.comment_approved = "1"
				',
                $user_id,
                $wpdb->comments,
                $wpdb->commentmeta,
                \PoPCMSSchema\CommentMeta\Utils::getMetaKey(GD_METAKEY_COMMENT_TAGGEDUSERS)
            );
        }

        return $select_from_comment_post_id_unions;
    }

    public function getUseractivitypostsCommentIdOrs($useractivityposts_comment_id_ors, $user_id)
    {
        global $wpdb;
        return array_merge(
            $useractivityposts_comment_id_ors,
            array(
                PoP_SocialNetwork_AddCommentsTinyMCE_Notifications_Utils::getCommentswheretheuseristaggedWhere($user_id),
            )
        );
    }

    public function getSelectFromCommentIdOrs($select_from_comment_id_ors, $user_id)
    {
        global $wpdb;
        return array_merge(
            $select_from_comment_id_ors,
            array(
                PoP_SocialNetwork_AddCommentsTinyMCE_Notifications_Utils::getCommentswheretheuseristaggedWhere($user_id),
            )
        );
    }
}

/**
 * Initialize
 */
new PoP_AddCommentsTinyMCE_SocialNetwork_Notifications_NotificationHooks();
