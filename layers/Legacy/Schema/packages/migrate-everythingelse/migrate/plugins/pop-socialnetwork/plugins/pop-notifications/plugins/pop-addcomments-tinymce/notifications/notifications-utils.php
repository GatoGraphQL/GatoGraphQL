<?php

class PoP_SocialNetwork_AddCommentsTinyMCE_Notifications_Utils
{
    public static function getCommentswheretheuseristaggedWhere($user_id)
    {
        global $wpdb;
        return sprintf(
            '
				comment_ID in (
					SELECT
						comment_id
					FROM
						%2$s
					WHERE
							meta_key = "%3$s"
						AND
							meta_value = %1$s
				)
			',
            $user_id,
            $wpdb->commentmeta,
            \PoPCMSSchema\CommentMeta\Utils::getMetaKey(GD_METAKEY_COMMENT_TAGGEDUSERS)
        );
    }

    public static function getUseractivitypostsPostIdUnions($user_id)
    {
        global $wpdb;
        return sprintf(
            '
				SELECT
					post_id
				FROM
					%2$s
				WHERE
						meta_key = "%3$s"
					AND
						meta_value = %1$s
			',
            $user_id,
            $wpdb->postmeta,
            \PoPCMSSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_TAGGEDUSERS)
        );
    }
}
