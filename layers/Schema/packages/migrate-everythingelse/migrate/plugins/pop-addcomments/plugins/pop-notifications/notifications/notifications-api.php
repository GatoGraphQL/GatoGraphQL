<?php

class PoP_AddComments_Notifications_API
{
    public static function clearComment($comment_id)
    {

        // Function called when a comment is deleted from the system
        global $wpdb;

        // Find the $histid to delete, since they'll have to be deleted in 2 tables: activity_log and activity_log_status
        $sql = $wpdb->prepare(
            '
			SELECT
				histid
			FROM
				`%1$s`
			WHERE 
				(
					`object_type` = "Comments"
				AND
					`object_id` = %2$d
				)
			',
            $wpdb->pop_notifications,
            $comment_id
        );
        if ($results = $wpdb->get_results($sql)) {
            $histids = array();
            foreach ($results as $result) {
                $histids[] = $result->histid;
            }

            PoP_Notifications_API::deleteNotifications($histids);
        }
    }
}
