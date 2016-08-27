<?php

/*
 * EXTENDED DIAGNOSTIC LOGGING - set value below from "0" to "1" to enable dx logging (or vice-versa).
 * Note: If the plugin is already activated, leave this alone and change the setting on the "Advanced" tab.
 * This option is intended only for logging installation and activation problems when the setting is not
 * available.
 * 
 * The diagnostic log should be written to a temporary table in your wp database.
 * 
 * When this is set to "1", logging will be attempted regardless of the setting.
 */
@define( 'GDE_DX_LOGGING', 0 );

// set up environment
if ( ! defined( 'ABSPATH' ) ) {	exit; }
@define( 'GDE_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
@define( 'GDE_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

// help links
@define( 'GDE_STDOPT_URL', 'https://wordpress.org/plugins/google-document-embedder/' );
@define( 'GDE_ENHOPT_URL', 'https://wordpress.org/plugins/google-document-embedder/' );
@define( 'GDE_PROOPT_URL', 'https://wordpress.org/plugins/google-document-embedder/' );
@define( 'GDE_ADVOPT_URL', 'https://wordpress.org/plugins/google-document-embedder/' );
@define( 'GDE_FORUM_URL', 'http://wordpress.org/support/plugin/google-document-embedder' );
@define( 'GDE_WP_URL', 'http://wordpress.org/extend/plugins/google-document-embedder/' );

/**
 * List supported extensions & MIME types
 *
 * @since   2.5.0.1
 * @return  array List of all supported extensions and their MIME types
 */
function gde_supported_types() {
	global $gdetypes;
	
	if ( is_array( $gdetypes ) ) {
		return $gdetypes;
	} else {
		$no_output = 1;
		include_once( GDE_PLUGIN_DIR . 'libs/lib-exts.php' );
		
		if ( isset( $types ) ) {
			return $types;
		} else {
			return false;
		}
	}
}

/**
 * Get profile data
 *
 * @since   2.5.0.1
 * @return  array Array of profiles with their data, or a specific profile
 */
function gde_get_profiles( $ident = '', $include_id = true, $include_desc = false ) {
	global $wpdb;
	$table = $wpdb->prefix . 'gde_profiles';
	
	if ( empty( $ident ) ) {
		$where = "WHERE 1 = 1 ORDER BY profile_id ASC";
	} elseif ( ! is_numeric( $ident ) ) {
		$where = "WHERE profile_name = '$ident'";
	} else {
		$where = "WHERE profile_id = $ident";
	}
	
	$profiles = $wpdb->get_results( "SELECT * FROM $table $where", ARRAY_A );
	
	if ( ! is_array( $profiles ) ) {
		gde_dx_log("Requested profile $ident not found");
		return false;
	} elseif ( ! empty( $ident ) ) {
		// return specific profile data
		if ( isset( $profiles[0] ) ) {
			$data = unserialize($profiles[0]['profile_data']);
			if ( $include_id ) {
				$data['profile_id'] = $profiles[0]['profile_id'];
			}
			if ( $include_desc ) {
				$data['profile_desc'] = $profiles[0]['profile_desc'];
			}
			
			$profiles = $data;
		} else {
			gde_dx_log("Requested profile $ident doesn't exist");
			return false;
		}
	}
	
	return $profiles;
}

/**
 * Test for valid shortcode syntax, other file errors
 *
 * @return  string Error message (if any)
 */
function gde_validate_file( $file = NULL, $force ) {
	
	// error messages
	$nofile = __('File not specified, check shortcode syntax', 'google-document-embedder');
	$badlink = __('Requested URL is invalid', 'google-document-embedder');
	$badtype = __('Unsupported File Type', 'google-document-embedder') . " (%e)";
	$unktype = __('Unable to determine file type from URL', 'google-document-embedder');
	$notfound = __('Error retrieving file - if necessary turn off error checking', 'google-document-embedder') . " (%e)";
	
	if ( ! $file ) {
		return $nofile;
	}
	
	$result = gde_valid_url( $file );
	if ( $result === false ) {
		// validation skipped due to service failure
		return -1;
	} elseif ( $force == "1" || $force == "yes" ) {
		if ( is_array( $result ) ) {
			return $result;
		} else {
			// couldn't get file size due to service failure
			return -1;
		}
	} elseif ( ! $result ) {
		// can't validate
		return -1;
	} else {
		if ( isset( $result['code'] ) && $result['code'] != 200 ) {
			if ( ! gde_valid_link( $file ) ) {
				return $badlink;
			} else {
				$err = $result['code'] . ":" . $result['message'];
				$notfound = str_replace( "%e", $err, $notfound );
				
				return $notfound;
			}
		} else {
			if ( ! gde_valid_type( $file ) ) {
				$fn = basename( $file );
				$fnp = gde_split_filename( $fn );
				$type = $fnp[1];
				
				if ( $type == '' ) {
					return $unktype;
				}
				$badtype = str_replace( "%e", $type, $badtype );
				
				return $badtype;
			} else {
				return $result;
			}
		}
	}
}

function gde_valid_link( $link ) {

    $urlregex = '/^(([\w]+:)?\/\/)(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/i';

    if ( preg_match( $urlregex, $link ) ) {
        return true;
    } else {
        return false;
    }
}

function gde_valid_type( $link ) {
	global $gdetypes;
	
	if ( is_array( $gdetypes ) ) {
		$supported_exts = implode( "|", array_keys( $gdetypes ) );
		
		if ( preg_match( "/\.($supported_exts)$/i", $link ) ) {
			return true;
		}
    } else {
        return false;
    }
}

function gde_valid_url( $url, $method = "head", $stop = 0 ) {

	if ( $method == "head" ) {
		$result = wp_remote_head( $url );
	} elseif ( $stop == 0 ) {
		$stop++;
		$result = wp_remote_get( $url );
	} else {
		gde_dx_log("can't get URL to test; skipping");
		return false;
	}
	
	if ( is_array( $result ) ) {
		$code = $result['response']['code'];
		if ( ! empty( $code ) && ( $code == "301" || $code == "302" ) ) {
			// HEAD requests don't redirect. Probably a file/directory with spaces in it...
			return gde_valid_url( $url, 'get', $stop );
		} else {
			// capture file size if determined
			if ( isset( $result['headers']['content-length'] ) ) {
				$result['response']['fsize'] = $result['headers']['content-length'];
			} else {
				$result['response']['fsize'] = '';
			}
			return $result['response'];
		}
	} elseif ( is_wp_error( $result ) ) {
		// unable to get head
		$error = $result->get_error_message();
		gde_dx_log("bypassing URL check; cant validate URL $url: $error");
		return false;
	} else {
		gde_dx_log("cant determine URL validity; skipping");
		return false;
	}
}

function gde_split_filename( $filename ) {
    $pos = strrpos( $filename, '.' );
    if ( $pos === false ) {
        return array( $filename, '' ); // no extension (dot is not found in the filename)
    } else {
        $basename = substr( $filename, 0, $pos );
        $ext = substr( $filename, $pos + 1 );
        return array( $basename, $ext );
    }
}

function gde_format_bytes( $bytes, $precision = 2 ) {
	if ( ! is_numeric( $bytes ) || $bytes < 1 ) {
		return __('Unknown', 'google-document-embedder');
	} else {
		$units = array( 'B', 'KB', __('MB', 'google-document-embedder'), 'GB', 'TB' );
		
		$bytes = max( $bytes, 0 );
		$pow = floor( ( $bytes ? log( $bytes ) : 0 ) / log( 1024 ) );
		$pow = min( $pow, count( $units ) - 1 );
		
		$bytes /= pow( 1024, $pow );
		
		if ( $units[$pow] == "KB" ) {
			// less precision for small file sizes
			return round( $bytes ) . "KB";
		} else {
			return round( $bytes, $precision ) . $units[$pow];
		}
	}
}

/**
 * Sanitize dimensions (width, height)
 *
 * @since   2.5.0.1
 * @return  string Sanitized dimensions, or false if value is invalid
 * @note	Replaces old gde_sanitizeOpts function
 */
function gde_sanitize_dims( $dim ) {
	// remove any spacing junk
	$dim = trim( str_replace( " ", "", $dim ) );
	
	if ( ! strstr( $dim, '%' ) ) {
		$type = "px";
		$dim = preg_replace( "/[^0-9]*/", '', $dim );
	} else {
		$type = "%";
		$dim = preg_replace( "/[^0-9]*/", '', $dim );
		if ( (int) $dim > 100 ) {
			$dim = "100";
		}
	}
	
	if ( $dim ) {
		return $dim.$type;
	} else {
		return false;
	}
}

/**
 * Shorten ("mask") the URL to the embedded file
 *
 * @since   2.5.0.1
 * @return  string Short url response from API call, or false on error
 */
function gde_get_short_url( $u ) {
	return $u; //bypass this function - breaks in current viewer
	
	$u = urlencode( $u );
	$service[] = "http://tinyurl.com/api-create.php?url=" . $u;
	$service[] = "http://is.gd/create.php?format=simple&url=" . $u;
	
	foreach ( $service as $url ) {
		$passed = false;
		$response = wp_remote_get( $url );
		if ( is_wp_error( $response ) || empty ( $response['body'] ) ) {
			continue;
		} else {
			// check for rate limit exceeded or other error response
			if ( ! gde_valid_link( $response['body'] ) ) {
				continue;
			} else {
				$passed = true;
				break;
			}
		}
	}
	
	if ( $passed ) {
		return $response['body'];
	} else {
		// can't shorten - return original URL
		gde_dx_log("Shorten URL failed: " . urldecode( $u ));
		return urldecode( $u );
	}
}

/**
 * Request secure URL to document
 *
 * @since   2.5.0.1
 * @return  string Secure URL, or false on error
 */
function gde_get_secure_url( $u ) {
	return $u; //bypass this function - breaks in current viewer
	
	require_once( GDE_PLUGIN_DIR . 'libs/lib-secure.php' );

	if ( ! $url = gde_make_secure_url( $u ) ) {
		return false;
	} else {
		return $url;
	}
}

/**
 * Check if settings allow a file to be privatized (downloads blocked)
 *
 * @since   2.5.0.2
 * @return  bool Whether or not the file can be blocked from download
 */
function gde_is_blockable( $profile ) {
	if ( $profile['viewer'] == "standard" || $profile['link_show'] !== "none" ) {
		return false;
	} elseif ( ! strstr( $profile['tb_flags'], "n" ) && $profile['tb_fullscr'] == "default" ) {
		return false;
	} else {
		return true;
	}
}

/**
 * Google Analytics Integration
 *
 * @since   2.5.0.1
 * @return  string GA tracking event tag, or blank if disabled
 */
function gde_ga_event( $file ) {
	global $gdeoptions;
	
	if ( $gdeoptions['ga_enable'] == "no" ) {
		return '';
	} elseif ( $gdeoptions['ga_enable'] == "compat" ) {
		$fnp = gde_split_filename( basename( $file ) );
		$category = "'Download'"; // intentionally not translated (it wasn't a translated string in < 2.5)
		$action = "'" . strtoupper( $fnp[1] ) . "'";
		$label = "this.href";
	} else {
		$category = "'" . addslashes( $gdeoptions['ga_category'] ) . "'";
		$action = "'" . __('Download', 'google-document-embedder') . "'";
		if ( $gdeoptions['ga_label'] == "url" ) {
			$label = "this.href";
		} else {
			$label = "'" . basename( $file ) . "'";
		}
	}
	
	$str = "_gaq.push(['_trackEvent', ".$category.", ".$action.", ".$label."]);";
	return " onClick=\"" . $str . "\"";
}

/**
 * Get plugin data
 *
 * @since   2.4.0.1
 * @return  array Array of plugin data parsed from main plugin file
 */
function gde_get_plugin_data() {
	$mainfile = 'gviewer.php';
	$slug = 'google-document-embedder';
	
	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}
	$plugin_data  = get_plugin_data( GDE_PLUGIN_DIR . $mainfile );
	
	// add custom data (lowercase to avoid any future conflicts)
	$plugin_data['slug'] = $slug;
	$plugin_data['mainfile'] = $mainfile;
	$plugin_data['basename'] = $plugin_data['slug'] . "/" . $mainfile;
	
	return $plugin_data;
}

/**
 * GDE Extended Logging
 *
 * @since   2.5.0.1
 * @return  bool Whether or not log write was successful
 */
function gde_dx_log( $text ) {
	global $gdeoptions, $wpdb;
	
	if ( GDE_DX_LOGGING > 0 || ( isset( $gdeoptions['error_log'] ) && $gdeoptions['error_log'] == "yes" ) ) {
		// filter to trap any "unexpected output" to log
		add_action( 'activated_plugin', 'gde_save_error' );
		
		$table = $wpdb->base_prefix . 'gde_dx_log';
		
		// create/update table if necessary, then write to log
		if ( gde_dx_log_create() ) {
			// write to log
			$blogid = get_current_blog_id();
			$text = date( "d-m-Y, H:i:s" ) . " - $text";
			
			if ( ! $wpdb->insert(
					$table,
					array(
						'blogid' 	=>	$blogid,
						'data'		=>	$text
					),
					array(
						'%d', '%s'
					)
				) ) {
				// couldn't write to db
				return false;
			} else {
				return true;
			}
		} else {
			// can't create db table
			return false;
		}
	} else {
		// logging disabled
		return false;
	}
}

/**
 * Create/update database table to store dx log
 *
 * @since   2.5.2.1
 * @return  bool Whether or not table creation/update was successful
 */
function gde_dx_log_create() {
	global $wpdb, $gde_db_ver;
	
	$table = $wpdb->base_prefix . 'gde_dx_log';
	$db_ver_installed = get_site_option( 'gde_db_version', 0 );
		
	$sql = "CREATE TABLE " . $table . " (
			  id mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT,
			  blogid smallint(5) UNSIGNED NOT NULL,
			  data longtext NOT NULL,
			  UNIQUE KEY (id)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8; ";
	
	if ( version_compare( $gde_db_ver, $db_ver_installed, ">" ) ) {
		// upgrade table if needed
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	} elseif ( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) == $table ) {
		// table's OK
		return true;
	} else {
		// table doesn't exist, try to create
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) == $table ) {
			// table's OK
			return true;
		} else {
			// can't create
			return false;
		}
	}
}

/**
 * Capture activation errors ("unexpected output") to dx log
 *
 * @since   2.5.0.1
 * @return  void
 * @note	Writes any activation errors to log, when enabled
 */
function gde_save_error() {
	$buffer = ob_get_contents();
	if ( ! empty( $buffer ) ) {
		gde_dx_log('Activation Msg: '. $buffer );
	}
}

function gde_show_error( $status ) {
	global $gdeoptions;
	
	$error = "GDE " . __('Error', 'google-document-embedder') . ": " . $status;
	if ( $gdeoptions['error_display'] == "no" ) {
		$code = "\n<!-- $error -->\n";
	} else {
		$code = "\n".'<div class="gde-error">' . $error . "</div>\n";
	}
	
	return $code;
}

/**
 * Check health of database tables
 *
 * @since   2.5.0.3
 * @return  mixed
 * @note	Verbose text used in debug information
 */
function gde_debug_tables( $table = array('gde_profiles', 'gde_secure'), $verbose = false ) {
	global $wpdb;
	
	if ( is_array( $table ) ) {
		$ok = true;
		foreach ( $table as $t ) {
			$t = $wpdb->prefix . $t;
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$t'" ) == $t ) {
				// table good
			} else {
				$ok = false;
				gde_dx_log($t . " table missing");
				break;
			}
		}
	} else {
		$ok = true;
		$table = $wpdb->prefix . $table;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) == $table ) {
			$s = "OK ";
			$c = $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE 1=1" );
			$s = $s . "($c items)";
		} else {
			$s = "NOT OK ($table missing)";
			$ok = false;
		}
	}
	
	if ( ! $verbose ) {
		return $ok;
	} else {
		return $s;
	}
}

?>
