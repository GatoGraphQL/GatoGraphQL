<?php

if ( ! defined( 'ABSPATH' ) ) {	exit; }

/* PROFILES ****/

/**
 * Write profile from form data
 *
 * @since   2.5.0.1
 * @return  bool Whether or not action successful
 */
function gde_form_to_profile( $pid, $data ) {

	// get current profile data
	$profile = gde_get_profiles( $pid, false );
	
	// initialize checkbox values (values if options unchecked)
	$profile['tb_flags'] = "przn";
	$profile['tb_fullwin'] = "same";
	$profile['tb_fulluser'] = "no";
	$profile['tb_print'] = "no";
	$profile['vw_flags'] = "";
	$profile['link_force'] = "no";
	$profile['link_mask'] = "no";
	$profile['link_block'] = "no";
	
	// enforce trailing slash on base_url
	$data['base_url'] = trailingslashit( $data['base_url'] );
	
	// sanitize width/height
	$data['default_width'] = gde_sanitize_dims( $data['default_width'] );
	$data['default_height'] = gde_sanitize_dims( $data['default_height'] );
	if ( ! $data['default_width'] ) {
		$data['default_width'] = $profile['default_width'];
	}
	if ( ! $data['default_height'] ) {
		$data['default_height'] = $profile['default_height'];
	}
	
	foreach ( $data as $k => $v ) {
		if ( array_key_exists( $k, $profile ) ) {
			// all fields where name == profile key
			$profile[$k] = stripslashes( $v );
		} elseif ( strstr( $k, 'gdet_' ) && ( strstr( $v, 'gdet_' ) ) ) {
			// toolbar checkboxes
			if ( $k == 'gdet_h' ) {
				$profile['tb_flags'] .= "h";
			} else {
				$profile['tb_flags'] = str_replace( str_replace( "gdet_", "", $v ), "", $profile['tb_flags'] );
			}
		} elseif ( $k == "fs_win" ) {
			$profile['tb_fullwin'] = "new";
		} elseif ( $k == "fs_user" ) {
			$profile['tb_fulluser'] = "yes";
		} elseif ( $k == "fs_print" ) {
			$profile['tb_print'] = "yes";
		} elseif ( strstr( $k, 'gdev_' ) && ( strstr( $v, 'gdev_' ) ) ) {
			$profile['vw_flags'] .= str_replace( "gdev_", "", $v );
		} elseif ( $k == "force" ) {
			$profile['link_force'] = "yes";
		} elseif ( $k == "mask" ) {
			$profile['link_mask'] = "yes";
		} elseif ( $k == "block" && gde_is_blockable( $profile ) ) {
			$profile['link_block'] = "yes";
		}
	}
	
	$newprofile = array( '', '', serialize( $profile ) );
	if ( gde_write_profile( $newprofile, $pid, true ) > 0 ) {
		// update successful
		return true;
	} else {
		return false;
	}
}

/**
 * Make new profile (from existing)
 *
 * @since   2.5.0.1
 * @return  bool Whether or not action successful
 */
function gde_profile_to_profile( $sourceid, $name, $desc = '' ) {
	global $wpdb;
	$table = $wpdb->prefix . 'gde_profiles';
	
	if ( $sourcedata = $wpdb->get_row( $wpdb->prepare( "SELECT profile_data FROM $table WHERE profile_id = %d", $sourceid ), ARRAY_A ) ) {
			$newprofile = array( $name, $desc, $sourcedata['profile_data'] );
			if ( gde_write_profile( $newprofile ) > 0 ) {
				return true;
			} else {
				return false;
			}
	} else {
		return false;
	}
}

/**
 * Create/update profile
 *
 * @since   2.5.0.1
 * @return  int 0 = fail, 1 = created, 2 = updated, 3 = nothing to do
 * @note	data array expected: [0] name, [1] desc, [2] serialized data
 */
function gde_write_profile( $data, $id = null, $overwrite = false ) {
	global $wpdb;
	$table = $wpdb->prefix . 'gde_profiles';
	
	if ( empty( $id ) ) {
		// get profile name
		$pname = strtolower( $data[0] );
		
		// new (non-default) profile
		if ( ! $wpdb->insert(
					$table,
					array(
						'profile_name'	=>	$pname,
						'profile_desc'	=>	$data[1],
						'profile_data'	=>	$data[2]
					)
				) ) {
			gde_dx_log("Failed to create profile '$pname'");
			return 0;
		} else {
			gde_dx_log("New profile '$pname' created");
			return 1;
		}
	} else {
		// new (default) or updated profile
		if ( is_null( $wpdb->get_row( "SELECT * FROM $table WHERE profile_id = $id" ) ) ) {
			// new default profile
			//gde_dx_log("Profile ID $id doesn't exist - creating");
			
			if ( ! $wpdb->insert(
						$table,
						array(
							'profile_id'	=>	$id,
							'profile_name'	=>	strtolower( $data[0] ),
							'profile_desc'	=>	$data[1],
							'profile_data'	=>	$data[2]
						),
						array(
							'%d', '%s', '%s', '%s'
						)
					) ) {
				gde_dx_log("Profile $id creation failed");
				return 0;
			} else {
				gde_dx_log("Profile $id created");
				return 1;
			}
		} elseif ( $overwrite ) {
			// get old data
			$olddata = gde_get_profiles( $id, false, true );
			$olddesc = $olddata['profile_desc'];
			unset( $olddata['profile_desc'] );
			
			// update profile
			gde_dx_log("Profile ID $id exists - updating");
			
			if ( ! empty( $data[0] ) ) {
				// overwrite name
				$newdata['profile_name'] = strtolower( $data[0] );
			}
			if ( ! empty( $data[1] ) && ( $data[1] !== $olddesc ) ) {
				// overwrite description
				$newdata['profile_desc'] = $data[1];
			}
			
			if ( ! empty( $data[2] ) && ( $data[2] !== serialize( $olddata ) ) ) {
				// overwrite data
				$newdata['profile_data'] = $data[2];
			}
			
			if ( isset( $newdata ) ) {
				if ( ! $wpdb->update(
							$table,
							$newdata,
							array( 'profile_id' => $id ), 
							array(
								'%s', '%s', '%s'
							)
						) ) {
					$info = print_r($newdata, true);
					gde_dx_log("Profile $id update failed writing: \n\n $info");
					return 0;
				} else {
					gde_dx_log("Profile $id updated");
					return 2;
				}
			} else {
				gde_dx_log("Overwrite requested but no changes found");
				return 3;
			}
		} else {
			gde_dx_log("Profile $id exists, overwrite not specified - nothing changed");
			return 3;
		}
	}
}

/**
 * Delete profile
 *
 * @since   2.5.0.1
 * @return  bool Whether or not action successful
 */
function gde_delete_profile( $id ) {
	global $wpdb;
	$table = $wpdb->prefix . 'gde_profiles';
	
	if ( $wpdb->query( $wpdb->prepare( "DELETE FROM $table WHERE profile_id = %d", $id ) ) > 0 ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check for duplicate profile name
 *
 * @since   2.5.0.2
 * @return  int Profile id of name or -1 if no match
 */
function gde_profile_name_exists( $name ) {
	global $wpdb;
	$table = $wpdb->prefix . 'gde_profiles';
	
	if ( $id = $wpdb->get_row( $wpdb->prepare( "SELECT profile_id FROM $table WHERE profile_name = %s", $name ), ARRAY_A ) ) {
		return (int) $id['profile_id'];
	} else {
		return -1;
	}
}

/**
 * Make existing profile data default (overwrite current default)
 *
 * @since   2.5.0.1
 * @return  bool Whether or not action successful
 */
function gde_overwrite_profile( $sourceid ) {
	global $wpdb;
	$table = $wpdb->prefix . 'gde_profiles';
	
	if ( $data = $wpdb->get_row( $wpdb->prepare( "SELECT profile_data FROM $table WHERE profile_id = %d", $sourceid ), ARRAY_A ) ) {
		if ( $wpdb->update ( $table, $data, array( 'profile_id' => 1 ), array( '%s' ) ) ) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/**
 * Process profile/settings import file
 *
 * @since   2.5.0.3
 * @return  void
 */
function gde_import( $data ) {
	$label = __('Import', 'google-document-embedder');
	$status = array();
	
	echo '
<div class="wrap">
	<div class="icon32" id="icon-plugins"></div>
	<h2>Google Doc Embedder ' . $label . '</h2>
	';
	
	if ( isset( $data['profiles'] ) || isset( $data['profiles'] ) ) {
		// full import
		echo "<p>" . __('Performing full import...', 'google-document-embedder') . "</p>\n";
		
		// profiles import
		if ( isset( $data['profiles'] ) ) {
			echo "<p>" . __('Importing profiles', 'google-document-embedder');
			
			$success = gde_import_profiles( $data['profiles'] );
			$total = count( $data['profiles'] );
			echo " ($success/$total)... ";
			if ( $success == $total ) {
				echo __('done', 'google-document-embedder') . ".</p>\n";
			} else {
				$status[] = "fail";
				echo "<strong>" . __('failed', 'google-document-embedder') . ".</strong></p>\n";
			}
		}
		
		// settings import
		if ( isset( $data['settings'] ) ) {
			echo "<p>" . __('Importing settings', 'google-document-embedder') . "... ";
			if ( ! gde_import_settings( $data['settings'] ) ) {
				$status[] = "fail";
				echo "<strong>" . __('failed', 'google-document-embedder') . ".</strong></p>\n";
			} else {
				echo __('done', 'google-document-embedder') . ".</p>\n";
			}
		}
	} elseif ( isset( $data[0]['profile_id'] ) ) {
		// profile import
		echo "<p>" . __('Importing profiles', 'google-document-embedder');
		
		$success = gde_import_profiles( $data );
		$total = count( $data );
		echo " ($success/$total)... ";
		if ( $success == $total ) {
			echo __('done', 'google-document-embedder') . ".</p>\n";
		} else {
			$status[] = "fail";
			echo "<strong>" . __('failed', 'google-document-embedder') . ".</strong></p>\n";
		}
	} elseif ( isset( $data['ed_disable'] ) ) {
		// settings import
		echo "<p>" . __('Importing settings... ', 'google-document-embedder');
		
		if ( ! gde_import_settings( $data ) ) {
			$status[] = "fail";
			echo "<strong>" . __('failed', 'google-document-embedder') . ".</strong></p>\n";
		} else {
			echo __('done', 'google-document-embedder') . ".</p>\n";
		}
	} else {
		echo "<p>" . __('Please select a valid export file to import.', 'google-document-embedder') . "</p>\n";
	}
	
	if ( in_array( 'fail', $status ) ) {
		echo "<p>" . __('All or part of the import failed. See above for information.', 'google-document-embedder') . "</p>\n";
	} else {
		echo "<p>" . __('Import completed successfully.', 'google-document-embedder') . "</p>\n";
	}
	
	echo "<p><a href=''>" . __('Return to GDE Settings', 'google-document-embedder') . "</a></p>\n";
	echo "</div>\n";
}

/**
 * Process settings import data
 *
 * @since   2.5.0.3
 * @return  bool Whether or not settings import succeeded
 */
function gde_import_settings( $data ) {
	global $gdeoptions;
	
	$current = $gdeoptions;
	
	if ( $current == $data ) {
		// nothing to do
		return true;
	} else {
		foreach ( $data as $k => $v ) {
			$gdeoptions[$k] = $v;
		}
		
		if ( update_option( 'gde_options', $gdeoptions ) ) {
			return true;
		} else {
			return false;
		}
	}
}

function gde_import_profiles( $data ) {
	$success = 0;
	
	foreach ( $data as $v ) {
		$pid = gde_profile_name_exists( $v['profile_name'] );
		if ( $pid !== -1 ) {
			// overwrite existing profile
			$prodata = array( '', $v['profile_desc'], $v['profile_data'] );
			if ( gde_write_profile( $prodata, $pid, true ) > 0 ) {
				$success++;
			} else {
				gde_dx_log("failure importing to overwrite profile $pid");
			}
		} else {
			// write as new profile
			$prodata = array( $v['profile_name'], $v['profile_desc'], $v['profile_data'] );
			if ( gde_write_profile( $prodata ) > 0 ) {
				$success++;
			} else {
				gde_dx_log("failure importing to new profile");
			}
		}
	}
	
	return $success;
}

/* SETTINGS ****/

/**
 * Get locale
 *
 * @since   2.4.1.1
 * @return  string Google viewer lang code based on WP_LANG setting, or en_US if not defined
 */
function gde_get_locale() {
	$locale = get_locale();
	
	require_once( GDE_PLUGIN_DIR . 'libs/lib-langs.php' );
	return gde_mapped_langs( $locale );
}

function gde_option_page() {
	global $gde_settings_page, $gdeoptions;
	
	$gde_settings_page = add_options_page( 'GDE '.__('Settings', 'google-document-embedder'), 'GDE '.__('Settings', 'google-document-embedder'), 'manage_options', 'gde-settings', 'gde_options' );
	
	// enable custom styles and settings jQuery
	add_action( 'admin_print_styles', 'gde_admin_custom_css' );
	add_action( 'admin_enqueue_scripts', 'gde_admin_custom_js' );
}

function gde_options() {
	if (! current_user_can('manage_options') ) wp_die('You don\'t have access to this page.');
	if (! user_can_access_admin_page()) wp_die( __('You do not have sufficient permissions to access this page', 'google-document-embedder') );
	
	require( GDE_PLUGIN_DIR . 'options.php' );
	add_action('in_admin_footer', 'gde_admin_footer');
}

/*
function gde_site_option_page() {
	global $gde_global_page;

	$gde_global_page = add_submenu_page( 'settings.php', 'GDE '.__('Settings', 'google-document-embedder'), 'GDE '.__('Settings', 'google-document-embedder'), 'manage_network_options', basename(__FILE__), 'gde_site_options' );

	// enable custom styles and settings jQuery
	//add_action( 'admin_print_styles', 'gde_admin_custom_css' );
	//add_action( 'admin_enqueue_scripts', 'gde_admin_custom_js' );
}

function gde_site_options() {
	//if ( function_exists('current_user_can') && !current_user_can('manage_options') ) wp_die('You don\'t have access to this page.');
	//if (! user_can_access_admin_page()) wp_die( __('You do not have sufficient permissions to access this page', 'google-document-embedder') );
	
	require( GDE_PLUGIN_DIR . 'site-options.php' );
	add_action( 'in_admin_footer', 'gde_admin_footer' );
}
*/

/**
 * Get Default Base URL
 *
 * @since   2.5.0.1
 * @return  string	Default base URL based on WP settings
 */
function gde_base_url() {
	if ( ! $baseurl = get_option( 'upload_url_path' ) ) {
		$uploads = wp_upload_dir();
		$baseurl = $uploads['baseurl'];
	}
	
	return trailingslashit( $baseurl );
}

/**
 * Display tabs
 *
 * @since   2.5.0.1
 * @return  void
 */
function gde_show_tab( $name ) {
	$tabfile = GDE_PLUGIN_DIR . "libs/tab-$name.php";
	
	if ( file_exists( $tabfile ) ) {
		include_once( $tabfile );
	}
}

/**
 * Reset global (multisite) options
 *
 * @since   2.5.0.1
 * @return  void
 */
/*
function gde_global_reset() {
	global $gdeglobals;
	
	// by default, global settings are empty
	if ($gdeglobals) {
		delete_site_option('gde_globals');
	}
}
*/

/**
 * Delete autoexpire secure docs
 *
 * @since   2.5.0.1
 * @note	Runs via wp-cron according to schedule defined in lib-setup
 * @return  void
 */
/*
function gde_sec_cleanup() {
	global $wpdb;
	
	$table = $wpdb->prefix . 'gde_secure';
	$wpdb->query( "DELETE FROM $table WHERE autoexpire = 'Y'" );
	gde_dx_log("Cleanup ran");
}
*/

/**
 * Include custom css for settings pages
 *
 * @since   2.5.0.1
 * @return  void
 */
function gde_admin_custom_css( $hook ) {
	global $wp_version;
	
	if ( isset( $_GET['page'] ) && ( $_GET['page'] == 'gde-settings' ) ) {
		if ( version_compare( $wp_version, '3.8-RC1', '>=' ) ) {
			$css = GDE_PLUGIN_URL . 'css/admin-styles38.css';
		} else {
			$css = GDE_PLUGIN_URL . 'css/admin-styles.css';
		}
		wp_enqueue_style( 'gde_css', $css );
		
		// native color picker
		wp_enqueue_style( 'wp-color-picker' );
	}
}

function gde_admin_footer() {
	global $pdata;
	
	$plugin_str = __('plugin', 'google-document-embedder');
	$version_str = __('Version', 'google-document-embedder');
	printf( '%1$s %2$s | %3$s %4$s<br />', $pdata['Title'], $plugin_str, $version_str, $pdata['Version'] );
}

function gde_show_msg( $message, $error = false ) {
	if ( $error ) { $class = "error"; } else { $class = "updated"; }
	echo '<div id="message" class="'.$class.'"><p>' . $message . '</p></div>';
}

// add additional links, for convenience
function gde_actlinks( $links ) { 
	$settings_link = '<a href="options-general.php?page=gde-settings">' . __('Settings', 'google-document-embedder') . '</a>'; 
	array_unshift( $links, $settings_link ); 
	return $links; 
}

function gde_admin_print_scripts( $arg ) {
	global $pagenow;
	if (is_admin() && ( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) ) {
		$js = GDE_PLUGIN_URL . 'js/gde-quicktags.js';
		wp_enqueue_script( 'gde_qts', $js, array('quicktags') );
	}
}

function gde_admin_custom_js( $hook ) {
	global $gde_settings_page, $gde_global_page, $pagenow;
	
	if ( $gde_settings_page == $hook || $gde_global_page == $hook ) {
		wp_enqueue_script( 'gde_jqs', GDE_PLUGIN_URL . 'js/gde-jquery.js', array( 'wp-color-picker' ), false, true );
		
		// localize
		wp_localize_script( 'gde_jqs', 'jqs_vars', array (
			// internal use
			'gde_url' => GDE_PLUGIN_URL,
			// profiles tab
			'delete' => __('This profile will be permanently deleted.', 'google-document-embedder') . "\n\n" . __('Are you sure?', 'google-document-embedder'),
			'default' => __('Settings for this profile will overwrite the default profile.', 'google-document-embedder') . "\n\n" . __('Are you sure?', 'google-document-embedder'),
			'reset' => __('Your profile list will be reset to its original state. All changes will be lost.', 'google-document-embedder') . "\n\n" . __('Are you sure?', 'google-document-embedder'),
			// advanced tab
			'badimport' => __('Please select a valid export file to import.', 'google-document-embedder'),
			'warnimport' => __('Any settings or duplicate profile names in this import will overwrite the current values.', 'google-document-embedder') . "\n\n" . __('Are you sure?', 'google-document-embedder'),
			// support tab
			'baddebug' => __('Please include a shortcode or message to request support.', 'google-document-embedder')
			)
		);
	}
}

/* MEDIA LIBRARY & EDITOR INTEGRATION ****/

/**
 * Modify the file insertion from Media Library if requested
 *
 * @since   2.4.0.1
 * @note	Requires WP 3.5+
 * @return  string HTML to insert into editor
 */
function gde_media_insert( $html, $id, $attachment ) {
	global $gdeoptions;

	$gdoc_url = '';
	if (isset($attachment['url'])) {
		$gdoc_url = $attachment['url'];
	}
	elseif ($id > 0) {
		$post = get_post($id);
		if ($post) {
			$gdoc_url = wp_get_attachment_url($id);
		}
	}
	
	if ($gdoc_url != '' && gde_valid_type( $gdoc_url ) && $gdeoptions['ed_embed_sc'] == "yes" ) {
		return '[gview file="' . $gdoc_url . '"]';
	} else {
		return $html;
	}
}

/**
 * Add upload support for natively unsupported mimetypes used by this plugin
 *
 * @since   2.4.0.1
 * @return  array Updated array of allowed upload types
 */
function gde_upload_mimes( $existing_mimes = array() ) {
	$supported_exts = gde_supported_types();
	
	foreach ( $supported_exts as $ext => $mimetype ) {
		if ( ! array_key_exists( $ext, gde_mimes_expanded( $existing_mimes ) ) ) {
			$existing_mimes[$ext] = $mimetype;
		}
	}
	return gde_mimes_collapsed( $existing_mimes );
}

function gde_mimes_expanded( array $types ) {
	// expand the supported mime types so that every ext is its own key
	foreach ( $types as $k => $v ) {
		if ( strpos( "|", $k ) ) {
			$subtypes = explode( "|", $k );
			foreach ( $subtypes as $type ) {
				$newtypes[$type] = $v;
				unset( $types[$k] );
			}
			$types = array_merge( $types, $newtypes );
		}
	}
	return $types;
}

function gde_mimes_collapsed( $types ) {
	// collapes the supported mime types so that each mime is listed once with combined key (default)
	$newtypes = array();
	
	foreach ( $types as $k => $v ) {
		if ( isset( $newtypes[$v] ) ) {
			$newtypes[$v] .= '|' . $k;
		} else {
            $newtypes[$v] = $k;
		}
	}
	return array_flip( $newtypes );
}

/**
 * Add TinyMCE button
 *
 * @since   2.0.0.1
 * @return  void
 */
function gde_mce_addbuttons() {
	// don't bother doing this stuff if the current user lacks permissions
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		return;
	
	// add only in Rich Editor mode
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "gde_add_tinymce_plugin");
		add_filter('mce_buttons', 'gde_register_mce_button');
   }
}

function gde_add_tinymce_plugin( $plugin_array ) {
	// load the TinyMCE plugin
	$plugin_array['gde'] = GDE_PLUGIN_URL . 'js/editor_plugin.js';
	return $plugin_array;
}

function gde_register_mce_button( $buttons ) {
	array_push( $buttons, "separator", "gde" );
	return $buttons;
}

/**
 * Check current beta status
 *
 * @since   2.5.0.1
 * @return  bool Whether or not the currently running version is a beta
 */
function gde_is_beta() {
	global $pdata;
	
	// check for currently running beta version (contains any letter or hyphen)
	if ( preg_match( '/[a-z-]/i', $pdata['Version'] ) ) {
		// running a beta
		return true;
	} else {
		return false;
	}
}

/**
 * Display beta status
 *
 * @since   2.5.0.1
 * @return  void
 */
function gde_warn_on_plugin_page( $plugin_file ) {
	global $pdata;
	
	if ( strstr( $plugin_file, $pdata['mainfile'] ) ) {
		
		// see if there's a release waiting first (prevent double messages)
		$updates = (array) get_site_option( '_site_transient_update_plugins' );
		if ( isset( $updates['response'] ) && array_key_exists( $pdata['basename'], $updates['response'] ) ) {
			return;
		}
		
		if ( gde_is_beta() ) {
			$message[] = __('You are running a pre-release version of Google Doc Embedder. Please watch this space for important updates.', 'google-document-embedder');
		} else {
			$message = array();
		}
		
		// print message if any
		$message = rtrim( implode( " ", $message ) );
		if ( ! empty( $message ) ) {
			// style improvements??
			//add_action( 'admin_enqueue_scripts', 'gde_admin_beta_js' );
			
			print('
				<tr class="plugin-update-tr">
					<td colspan="3" class="plugin-update colspanchange">
						<div class="update-message" style="background:#e3e3e3;">
						'.$message.'
						</div>
					</td>
				</tr>
			');
		}
	}
}

/**
 * Check for existence and valid content of dx log
 *
 * @since   2.5.2.1
 * @return  bool
 */
function gde_log_available() {
	global $wpdb;
	
	$table = $wpdb->base_prefix . 'gde_dx_log';
	$blogid = get_current_blog_id();
	$log = false;
	
	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) == $table ) {
		$c = $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE blogid = $blogid" );
		if ( $c > 0 ) {
			$log = true;
		}
	}
	
	return $log;
}

?>
