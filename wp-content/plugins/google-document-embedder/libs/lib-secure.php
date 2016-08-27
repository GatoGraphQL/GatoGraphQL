<?php

/**
 *	Generates and stores "secure" URLs for private documents.
 */

if ( ! function_exists('gde_activate') ) {
	// no access if parent plugin is disabled or when accessed directly
	wp_die('<p>'.__('You do not have sufficient permissions to access this page.').'</p>');
}

/**
 * Generate and store secure document link
 *
 * @since	2.5.0.3
 * @return  string Secure document link or false on failure
 */
function gde_make_secure_url( $url ) {
	global $wpdb;

	$table = $wpdb->prefix . 'gde_secure';
	$data = $wpdb->get_results( "SELECT * FROM $table WHERE url = '$url'", ARRAY_A );

	if ( ! empty( $data ) ) {
		// a secure url to this doc already exists
		$data = $data[0];
		return $data['murl'];
	} else {
		// make a new entry
		$newcode = gde_secure_code();
		$url_to_mask = GDE_PLUGIN_URL . "load.php?s=" . $newcode;
		$masked_url = gde_get_short_url( $url_to_mask );

		if ( ! $wpdb->insert(
				$table,
				array(
					'code'	=>	$newcode,
					'url'	=>	$url,
					'murl'	=>	$masked_url
				),
				array(
					'%s', '%s', '%s'
				)
			) ) {
			gde_dx_log("Unable to record secure URL in database");
			return false;
		} else {
			return $masked_url;
		}
	}
}

/**
 * Generate random document code
 *
 * @since	2.5.0.1
 * @return  string Random string used for secure doc link in database
 */
function gde_secure_code( $length = 10 ) {
	$alpha = 'aeioubdghjmnpqrstvyz';
	$alpha .= strtoupper( $alpha );
	$numer = '1234567890';
	 
	$code = '';
	$alt = time() % 2;
	for ( $i = 0; $i < $length; $i++ ) {
		if ( $alt == 1 ) {
			$code .= $alpha[(rand() % strlen( $alpha ))];
			$alt = 0;
		} else {
			$code .= $numer[(rand() % strlen( $numer ))];
			$alt = 1;
		}
	}
	return $code;
}

?>
