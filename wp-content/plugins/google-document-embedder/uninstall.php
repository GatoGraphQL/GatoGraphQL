<?php

global $wpdb;

if ( is_multisite() ) {
	// multisite cleanup
	delete_site_option( 'gde_db_version' );
	delete_site_option( 'gde_globals' );
	delete_site_transient( 'gde_beta_version' );
	
	$blogids = $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM '%s'", $wpdb->blogs ) );
	foreach ( $blogids as $blogid ) {
		switch_to_blog( $blogid );
		
		delete_option( 'gde_options' );
		
		$table = $wpdb->prefix . 'gde_profiles';
		$wpdb->query( "DROP TABLE IF EXISTS $table" );
		$table = $wpdb->prefix . 'gde_secure';
		$wpdb->query( "DROP TABLE IF EXISTS $table" );
	}
	restore_current_blog();
} else {
	// standalone cleanup
	delete_option( 'gde_db_version' );
	delete_transient( 'gde_beta_version' );
	
	$table = $wpdb->prefix . 'gde_profiles';
	$wpdb->query( "DROP TABLE IF EXISTS $table" );
	$table = $wpdb->prefix . 'gde_secure';
	$wpdb->query( "DROP TABLE IF EXISTS $table" );
}

// delete options and beta cache
delete_option( 'gde_options' );
delete_option( 'external_updates-google-document-embedder' );

// drop dx log table, if any
$table = $wpdb->base_prefix . 'gde_dx_log';
$wpdb->query( "DROP TABLE IF EXISTS $table" );

?>
