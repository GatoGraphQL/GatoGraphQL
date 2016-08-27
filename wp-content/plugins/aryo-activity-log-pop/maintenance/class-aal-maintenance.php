<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AAL_PoP_Maintenance {

	public static function activate( $network_wide ) {
		global $wpdb;

		if ( function_exists( 'is_multisite') && is_multisite() && $network_wide ) {
			$old_blog_id = $wpdb->blogid;

			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				self::_create_tables();
			}

			switch_to_blog( $old_blog_id );
		} else {
			self::_create_tables();
		}
	}

	public static function mu_new_blog_installer( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
		global $wpdb;

		if ( is_plugin_active_for_network( AAL_POP_LOG_BASE ) ) {
			$old_blog_id = $wpdb->blogid;
			switch_to_blog( $blog_id );
			self::_create_tables();
			switch_to_blog( $old_blog_id );
		}
	}

	public static function mu_delete_blog( $blog_id, $drop ) {
		global $wpdb;

		$old_blog_id = $wpdb->blogid;
		switch_to_blog( $blog_id );
		self::_remove_tables();
		switch_to_blog( $old_blog_id );
	}

	protected static function _create_tables() {

		global $wpdb;

		// For the columns, use `status_histid` and `status_user_id` instead of `histid` and `user_id`
		// because we are joining tables aryo_activity_log and aryo_activity_log_status with a LEFT OUTER JOIN,
		// where table aryo_activity_log_status will not have the status record if the user has yet not
		// seen the activity, so in that case this table's user_id and histid columns will be null. Yet,
		// in the returned WP object, these 2 records will overwrite the corresponding records from table
		// aryo_activity_log, overwriting these 2 values with null. So make sure these columns names are different
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}aryo_activity_log_status` (
					  `statusid` int(11) NOT NULL AUTO_INCREMENT,
					  `status_histid` int(11) NOT NULL DEFAULT '0',
					  `status_user_id` int(11) NOT NULL DEFAULT '0',
					  `status` varchar(255) NOT NULL,
					  PRIMARY KEY (`statusid`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		update_option( 'aal_pop_db_version', '1.0' );
	}

	protected static function _remove_tables() {
		global $wpdb;

		$wpdb->query( "DROP TABLE IF EXISTS `{$wpdb->prefix}aryo_activity_log_status`;" );

		delete_option( 'aal_pop_db_version' );
	}
}

register_activation_hook( AAL_POP_LOG_BASE, array( 'AAL_PoP_Maintenance', 'activate' ) );
// register_uninstall_hook( AAL_POP_LOG_BASE, array( 'AAL_PoP_Maintenance', 'uninstall' ) );

// // MU installer for new blog.
// add_action( 'wpmu_new_blog', array( 'AAL_PoP_Maintenance', 'mu_new_blog_installer' ), 10, 6 );
// // MU Uninstall for delete blog.
// add_action( 'delete_blog', array( 'AAL_PoP_Maintenance', 'mu_delete_blog' ), 10, 2 );