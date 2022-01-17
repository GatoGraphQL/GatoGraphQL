<?php

class PoP_SocialNetwork_Notifications_Utils
{
    public static function getPosthashashtagtheuserissubscribedtoWhere($user_id)
    {

        // Posts which have a #hastag that the user is subscribed to
        global $wpdb;
        return sprintf(
            '
				SELECT
					object_id
				FROM
					%2$s
				INNER JOIN
					%3$s
				ON
					%2$s.term_taxonomy_id = %3$s.term_taxonomy_id
				WHERE
					%3$s.term_id in (
						SELECT
							meta_value
						FROM
							%4$s
						WHERE
								user_id = %1$s
							AND
								meta_key = "%5$s"
					)
			',
            $user_id,
            $wpdb->term_relationships,
            $wpdb->term_taxonomy,
            $wpdb->usermeta,
            \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS)
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
