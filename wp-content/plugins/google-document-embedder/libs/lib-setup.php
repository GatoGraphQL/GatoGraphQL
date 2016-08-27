<?php

if ( ! function_exists('gde_activate') ) {
	// no access if parent plugin is disabled or when accessed directly
	wp_die('<p>'.__('You do not have sufficient permissions to access this page.').'</p>');
}

/**
 * Define system defaults (settings/profiles)
 *
 * @since	2.5.5.1
 * @return  mixed
 */
function gde_defaults( $type ) {
	global $env;
	
	// gather/set environment info
	if ( ! $env ) {
		$pdata = gde_get_plugin_data();
		$baseurl = gde_base_url();
		$default_lang = gde_get_locale();
		$apikey = '';
		$env = array(
			'pdata'				=>	$pdata,
			'baseurl'			=>	$baseurl,
			'default_lang'		=>	$default_lang
		);
	}
	
	// define "global" options (multisite only)
	$globalopts = array(
		'file_maxsize'			=>	'12',
		'beta_check'			=>	'no'
	);
	
	// define default options
	$defopts = array(
		'ed_disable'			=>	'no',
		'ed_extend_upload'		=>	'yes',
		'ed_embed_sc'			=>	'yes',
		'file_maxsize'			=>	'12',
		'error_check'			=>	'yes',
		'error_display'			=>	'yes',
		'error_log'				=>	'no',
		'beta_check'			=>	'no',
		'ga_enable'				=>	'no',
		'ga_category'			=>	$env['pdata']['Name'],
		'ga_label'				=>	'url'
	);
	
	// define default profile(s)
	$defpros = array(
		"default" => array(
			"desc"	=>	__('This is the default profile, used when no profile is specified.', 'google-document-embedder'),
			"viewer"			=>	'standard',
			"default_width"		=>	'100%',
			"default_height"	=>	'500px',
			"tb_mobile"			=>	'default',
			"tb_flags"			=>	'',
			"tb_fullscr"		=>	'default',
			"tb_fullwin"		=>	'new',
			"tb_fulluser"		=>	'no',
			"tb_print"			=>	'no',
			"vw_bgcolor"		=>	'#EBEBEB',
			"vw_pbcolor"		=>	'#DADADA',
			"vw_css"			=>	'',
			"vw_flags"			=>	'',
			"language"			=>	$env['default_lang'],
			"base_url"			=>	$env['baseurl'],
			"link_show"			=>	'all',
			"link_mask"			=>	'no',
			"link_block"		=>	'no',
			"link_text"			=>	__('Download', 'google-document-embedder') . ' (%TYPE, %SIZE)',
			"link_pos"			=>	'below',
			"link_force"		=>	'no',
			"cache"				=>	'on'
		),
		"max-doc-security" => array(
			"desc"	=>	__('Hide document location and text selection, prevent downloads', 'google-document-embedder'),
			"viewer"			=>	'enhanced',
			"default_width"		=>	'100%',
			"default_height"	=>	'500px',
			"tb_mobile"			=>	'default',
			"tb_flags"			=>	'',
			"tb_fullscr"		=>	'viewer',
			"tb_fullwin"		=>	'new',
			"tb_fulluser"		=>	'no',
			"tb_print"			=>	'no',
			"vw_bgcolor"		=>	'#EBEBEB',
			"vw_pbcolor"		=>	'#DADADA',
			"vw_css"			=>	'',
			"vw_flags"			=>	'',
			"language"			=>	$env['default_lang'],
			"base_url"			=>	$env['baseurl'],
			"link_show"			=>	'none',
			"link_mask"			=>	'no',
			"link_block"		=>	'yes',
			"link_text"			=>	'',
			"link_pos"			=>	'below',
			"link_force"		=>	'no',
			"cache"				=>	'on'
		),
		"dark" => array(
			"desc"	=>	__('Dark-colored theme, example of custom CSS option', 'google-document-embedder'),
			"viewer"			=>	'enhanced',
			"default_width"		=>	'100%',
			"default_height"	=>	'500px',
			"tb_mobile"			=>	'default',
			"tb_flags"			=>	'',
			"tb_fullscr"		=>	'viewer',
			"tb_fullwin"		=>	'new',
			"tb_fulluser"		=>	'no',
			"tb_print"			=>	'no',
			"vw_bgcolor"		=>	'',
			"vw_pbcolor"		=>	'',
			"vw_css"			=>	GDE_PLUGIN_URL . 'css/gde-dark.css',
			"vw_flags"			=>	'',
			"language"			=>	$env['default_lang'],
			"base_url"			=>	$env['baseurl'],
			"link_show"			=>	'all',
			"link_mask"			=>	'no',
			"link_block"		=>	'no',
			"link_text"			=>	__('Download', 'google-document-embedder') . ' (%TYPE, %SIZE)',
			"link_pos"			=>	'below',
			"link_force"		=>	'no',
			"cache"				=>	'on'
		)
	);
	
	switch ( $type ) {
		case "globals":
			return $globalopts;
			break;
		case "options":
			return $defopts;
			break;
		case "profiles":
			return $defpros;
			break;
		default:
			gde_dx_log('Defaults requested but type not specified');
			return false;
			break;
	}
}

/**
 * Perform activation
 *
 * @since	2.5.0.1
 * @return  void
 */
function gde_setup() {
	if ( GDE_DX_LOGGING > 0 ) {
		gde_dx_log("Dx log manually enabled in functions.php");
	}
	
	// clear any beta transient
	gde_dx_log("Clearing beta cache");
	delete_site_transient( 'gde_beta_version' );
	delete_transient( 'gde_beta_version' );
	delete_option( 'external_updates-google-document-embedder' );
	
	gde_dx_log("Activating...");
	
	if ( is_multisite() ) {
		if ( ! $gdeglobals = get_site_option( 'gde_globals' ) ) {
			gde_dx_log("Writing multisite global options");
			$globalopts = gde_defaults('global');
			update_site_option( 'gde_globals', $globalopts );
		}
	}
	
	// check for existing or updated options
	$gdeoptions = gde_get_options();
	
	// check for existence of default profile (re-activation?)
	if ( ! gde_get_profiles( 1 ) ) {
		// new activation - write profile(s)
		$defpros = gde_defaults('profiles');
		foreach ( $defpros as $key => $prodata ) {
			if ( $key == "default" ) {
				$id = 1; // default profile is always ID 1
			} else {
				$id = null;	// assign next id
			}
			
			// prepare profile
			$desc = $prodata['desc'];
			unset( $prodata['desc'] );
			
			// write profile
			$data = serialize( $prodata );
			$profile = array( $key, $desc, $data );
			if ( gde_write_profile( $profile, $id ) < 1 ) {
				gde_dx_log("Failed to write profile '$key'");
			}
		}
	} else {
		gde_dx_log("Profiles already exist");
		update_profiles();
	}
	
	gde_dx_log("Activation complete.");
}

/**
 * Upgrade profiles if changes have been made
 *
 * @since	2.5.5.1
 * @return  void
 */
function update_profiles() {
	$prodata = gde_get_profiles();
	$defpros = gde_defaults('profiles');
	$default = $defpros['default'];
	
	foreach ( $prodata as $profile ) {
		$updated = false;
		
		$id = $profile['profile_id'];
		if ( $data = @unserialize( $profile['profile_data'] ) ) {
			foreach ( $default as $k => $v ) {
				if ( $k !== "desc" && ! array_key_exists( $k, $data ) ) {
					$data[$k] = $default[$k];
					
					$updated = true;
				}
			}
			
			if ( $updated ) {
				// write updated profile
				$data = serialize( $data );
				$newpro = array( $profile['profile_name'], $profile['profile_desc'], $data );
				if ( gde_write_profile( $newpro, $id, true ) < 1 ) {
					gde_dx_log("Failed to update profile '" . $profile['profile_name'] . "'");
				}
			}
		}
	}
}

/**
 * Get the current options, upgrading or resetting them as needed
 *
 * @since	2.5.5.1
 * @return  array Current value of gde_options
 */
function gde_get_options() {
	$defopts = gde_defaults('options');
	
	if ( ! $gdeoptions = get_option('gde_options') ) {
		// options don't exist
		gde_dx_log("Writing default options");
		update_option('gde_options', $defopts);
	} else {
		// check if upgrading from < 2.5
		if ( isset( $gdeoptions['default_width'] ) ) {
			gde_dx_log("Old options found - resetting");
			$defopts['upgraded'] = "yes";
			update_option('gde_options', $defopts);
		} else {
			gde_dx_log("Options already exist");
			
			// check or upgrade options
			$updated = false;
			foreach ( $defopts as $k => $v ) {
				if ( ! array_key_exists( $k, $gdeoptions ) ) {
					$gdeoptions[$k] = $v;
					//gde_dx_log("New option $k added");
					$updated = true;
				}
			}
			
			if ( $updated ) {
				gde_dx_log('Options were updated');
				update_option('gde_options', $defopts);
			}
		}
	}
	
	return $gdeoptions;
}

/**
 * Create/update database table to store profile data
 *
 * @since   2.5.0.1
 * @return  bool Whether or not table creation/update was successful
 */
function gde_db_tables( $gde_db_ver ) {
	global $wpdb;
	
	// attempt to trap table creation failures
	$fails = 0;
	
	// check for missing required tables (clear db version)
	$table = $wpdb->prefix . 'gde_profiles';
	if ($wpdb->get_var( "SHOW TABLES LIKE '$table'" ) !== $table) {
		//gde_dx_log("profiles db failed health check");
		delete_site_option( 'gde_db_version' );
	}
	
	$table = $wpdb->prefix . 'gde_secure';
	if ($wpdb->get_var( "SHOW TABLES LIKE '$table'" ) !== $table) {
		//gde_dx_log("securedoc db failed health check");
		delete_site_option( 'gde_db_version' );
	}
	
	if ( is_multisite() ) {
		$db_ver_installed = get_site_option( 'gde_db_version', 0 );
	} else {
		$db_ver_installed = get_option( 'gde_db_version', 0 );
	}
	
	gde_dx_log("Installed DB ver: $db_ver_installed; This DB ver: " . $gde_db_ver );
	if ( version_compare( $gde_db_ver, $db_ver_installed, ">" ) ) {
		// install or upgrade profile table
		$table = $wpdb->prefix . 'gde_profiles';
	
		$sql = "CREATE TABLE " . $table . " (
		  profile_id mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT,
		  profile_name varchar(64) NOT NULL,
		  profile_desc varchar(255) NULL,
		  profile_data longtext NOT NULL,
		  UNIQUE KEY (profile_id)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8; ";

		if ( isset( $sql ) ) {
			// write table or update to database
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta($sql);
			
			if ($wpdb->get_var( "SHOW TABLES LIKE '$table'" ) == $table ) {
				gde_dx_log("Profile table create/update successful");
			} else {
				gde_dx_log("Profile table create/update failed");
				$fails++;
			}
		}
		
		// install or upgrade securedoc table
		$table = $wpdb->prefix . 'gde_secure';
		$sql = "CREATE TABLE " . $table . " (
		  code varchar(10) NOT NULL,
		  url varchar(255) NOT NULL,
		  murl varchar(100) NOT NULL,
		  stamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  autoexpire enum('Y','N') NOT NULL DEFAULT 'N',
		  UNIQUE KEY code (code)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8; ";
		
		if ( isset( $sql ) ) {
			// write table or update to database
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) == $table ) {
				gde_dx_log("Secure doc table create/update successful");
			} else {
				gde_dx_log("Secure doc table create/update failed");
				$fails++;
			}
		}
	} else {
		gde_dx_log("Tables OK, nothing to do");
	}
	
	if ( $fails > 0 ) {
		delete_site_option( 'gde_db_version' );
		return false;
	} else {
		update_site_option( 'gde_db_version', $gde_db_ver );
		return true;
	}
}

?>
