<?php

class AAL_PoP_AdaptationUtils
{
    public static function swapDbTable()
    {

        // Swap the DB table to the one for the notifications
        global $wpdb;
        $wpdb->activity_log = $wpdb->prefix.'pop_notifications';
    }

    public static function swappedDbTable()
    {
        global $wpdb;
        $wpdb->activity_log == $wpdb->prefix.'pop_notifications';
    }

    public static function restoreDbTable()
    {

        // Restore to the original AAL DB table
        global $wpdb;
        $wpdb->activity_log = $wpdb->prefix.'aryo_activity_log';
    }
}
