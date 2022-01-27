<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AAL_PoP_Maintenance
{
    public static function activate($network_wide)
    {
        global $wpdb;

        if (function_exists('is_multisite') && is_multisite() && $network_wide) {
            $old_blog_id = $wpdb->blogid;

            $blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
            foreach ($blog_ids as $blog_id) {
                switch_to_blog($blog_id);
                self::_create_tables();
            }

            switch_to_blog($old_blog_id);
        } else {
            self::_create_tables();
        }
    }

    public static function muNewBlogInstaller($blog_id, $user_id, $domain, $path, $site_id, $meta)
    {
        global $wpdb;

        if (is_plugin_active_for_network(AAL_POP_LOG_BASE)) {
            $old_blog_id = $wpdb->blogid;
            switch_to_blog($blog_id);
            self::_create_tables();
            switch_to_blog($old_blog_id);
        }
    }

    public static function muDeleteBlog($blog_id, $drop)
    {
        global $wpdb;

        $old_blog_id = $wpdb->blogid;
        switch_to_blog($blog_id);
        self::_remove_tables();
        switch_to_blog($old_blog_id);
    }

    protected static function _create_tables()
    {
        global $wpdb;

        // Recreate the same table as aryo_activity_log, however called pop_notifications.
        // This way, we can reuse plugin AAL without having both the activity log and the notifications on the same DB.
        // Then, users are able to use PoP Notifications without restraining their use of AAL (they may be users of that plugin already!)
        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}pop_notifications` (
					  `histid` int(11) NOT NULL AUTO_INCREMENT,
					  `user_caps` varchar(70) NOT NULL DEFAULT 'guest',
					  `action` varchar(255) NOT NULL,
					  `object_type` varchar(255) NOT NULL,
					  `object_subtype` varchar(255) NOT NULL DEFAULT '',
					  `object_name` varchar(255) NOT NULL,
					  `object_id` int(11) NOT NULL DEFAULT '0',
					  `user_id` int(11) NOT NULL DEFAULT '0',
					  `hist_ip` varchar(55) NOT NULL DEFAULT '127.0.0.1',
					  `hist_time` int(11) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`histid`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        // For the columns, use `status_histid` and `status_user_id` instead of `histid` and `user_id`
        // because we are joining tables aryo_activity_log and aryo_activity_log_status with a LEFT OUTER JOIN,
        // where table aryo_activity_log_status will not have the status record if the user has yet not
        // seen the activity, so in that case this table's user_id and histid columns will be null. Yet,
        // in the returned WP object, these 2 records will overwrite the corresponding records from table
        // aryo_activity_log, overwriting these 2 values with null. So make sure these columns names are different
        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}pop_notifications_status` (
					  `statusid` int(11) NOT NULL AUTO_INCREMENT,
					  `status_histid` int(11) NOT NULL DEFAULT '0',
					  `status_user_id` int(11) NOT NULL DEFAULT '0',
					  `status` varchar(255) NOT NULL,
					  PRIMARY KEY (`statusid`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

        // require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
        
        update_option('aal_pop_db_version', '1.0');
    }

    protected static function _remove_tables()
    {
        global $wpdb;

        $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}pop_notifications_status`;");

        delete_option('aal_pop_db_version');
    }
}

register_activation_hook(AAL_POP_LOG_BASE, array( 'AAL_PoP_Maintenance', 'activate' ));
// register_uninstall_hook( AAL_POP_LOG_BASE, array( 'AAL_PoP_Maintenance', 'uninstall' ) );

// // MU installer for new blog.
// \PoP\Root\App::addAction( 'wpmu_new_blog', array( 'AAL_PoP_Maintenance', 'muNewBlogInstaller' ), 10, 6 );
// // MU Uninstall for delete blog.
// \PoP\Root\App::addAction( 'delete_blog', array( 'AAL_PoP_Maintenance', 'muDeleteBlog' ), 10, 2 );
