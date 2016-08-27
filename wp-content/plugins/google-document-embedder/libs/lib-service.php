<?php

	/*
	 * Web service for import/export handling
	 */

// access wp functions externally
require_once('lib-bootstrap.php');

// no access if parent plugin is disabled
if ( ! function_exists('gde_activate') ) {
	wp_die('<p>'.__('You do not have sufficient permissions to access this page.').'</p>');
}

if ( isset( $_REQUEST['json'] ) ) {
	switch ( $_REQUEST['json'] ) {
		case "profiles":
			if ( isset( $_REQUEST['id'] ) ) {
				$profiles = gde_get_profiles( $_REQUEST['id'] );
				$suffix = "-" . $_REQUEST['id'];
			} else {
				$profiles = gde_get_profiles();
				$suffix = '';
			}
			
			if ( is_array( $profiles ) ) {
				if ( isset( $_REQUEST['save'] ) && $_REQUEST['save'] == "1" ) {
					$file = 'gde-profiles' . $suffix;
					gde_output_json( json_encode( $profiles ), true, $file );
				} else {
					gde_output_json( json_encode( $profiles ) );
				}
			} else {
				echo "0";
				exit;
			}
		case "settings":
			if ( isset( $_REQUEST['save'] ) && $_REQUEST['save'] == "1" ) {
				$file = 'gde-settings';
				gde_output_json( json_encode( $gdeoptions ), true, $file );
			} else {
				gde_output_json( json_encode( $gdeoptions ) );
			}
		case "all":
			$data['profiles'] = gde_get_profiles();
			$data['settings'] = $gdeoptions;
			
			if ( isset( $_REQUEST['save'] ) && $_REQUEST['save'] == "1" ) {
				unset( $data['settings']['api_key'] );
				$file = 'gde-export';
				gde_output_json( json_encode( $data ), true, $file );
			} else {
				gde_output_json( json_encode( $data ) );
			}
		default:
			wp_die('<p>'.__('You do not have sufficient permissions to access this page.').'</p>');
	}
} elseif ( isset( $_REQUEST['viewlog'] ) ) {
	// request to view dx log
	global $wpdb;
	
	$blogid = get_current_blog_id();
	
	$table = $wpdb->base_prefix . 'gde_dx_log';
	$check = $wpdb->query("SHOW TABLES LIKE '" . $table . "'");
	//if ( mysql_num_rows( $check ) > 0 ) {
	if ( $check ) {
		$data = $wpdb->get_col( "SELECT * FROM $table WHERE blogid = '$blogid' ORDER BY id ASC", 2 );
		header('Content-type: text/plain');
		if ( is_array( $data ) ) {
			foreach ( $data as $v ) {
				echo $v . "\n";
			}
		}
	}
} else {
	wp_die('<p>'.__('You do not have sufficient permissions to access this page.').'</p>');
}

/**
 * Output/download requested JSON data
 *
 * @since   2.5.0.2
 * @return  void
 */
function gde_output_json( $json, $save = false, $file = '' ) {
	if ( $save && ! empty( $file ) ) {
		header('Content-disposition: attachment; filename=' . $file . '.json' );
	}
	header('Content-type: application/json');
	echo $json;
	exit;
}

?>
